<?php

namespace App\Services\Message\Actions;

use App\Core\Exceptions\HttpException;
use App\Core\Parents\Action;
use App\Services\File\Jobs\DeleteFiles;
use App\Services\File\Models\File;
use App\Services\File\Tasks\AttachFilesToMessageTask;
use App\Services\Message\Dto\UpdateMessageDto;
use App\Services\Message\Models\Message;
use App\Services\Message\Tasks\FindMessageTask;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class UpdateMessageAction extends Action
{
    /**
     * Обновляет сообщение
     */
    public function run(UpdateMessageDto $dto): Message
    {
        $message = $this
            ->task(FindMessageTask::class)
            ->run($dto->messageId)
            ->load('files');
        $this->validate($message, $dto);

        try {
            return $this->updateMessage($message, $dto);
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new HttpException(500, 'Не получилось обновить сообщение');
        }
    }

    /**
     * Проверяет возможность изменения сообщения
     */
    private function validate(Message $message, UpdateMessageDto $dto): void
    {
        $message->canUpdate();

        $newFileUuids = $dto->fileUuids;
        $oldFileUuids = $message->files->pluck('uuid')->toArray();
        if (
            $dto->text === $message->text
            && empty(array_diff($newFileUuids, $oldFileUuids))
            && empty(array_diff($oldFileUuids, $newFileUuids))
        ) {
            throw new HttpException(403, 'Сообщение должно отличаться');
        }
    }


    /**
     * Обновляет сообщение
     */
    private function updateMessage(Message $message, UpdateMessageDto $dto): Message
    {
        return DB::transaction(function () use ($message, $dto) {
            if ($message->text !== $dto->text) {
                $message->text = $dto->text;
                $message->save();
            }

            $oldFileUuids = $message->files->pluck('uuid')->toArray();
            $deletedFileUuids = array_diff($oldFileUuids, $dto->fileUuids);
            if (!empty($deletedFileUuids)) {
                $this->deleteUnusedFiles($message->files, $deletedFileUuids);
            }

            $newFileUuids = array_diff($dto->fileUuids, $oldFileUuids);
            if (!empty($newFileUuids)) {
                $this->task(AttachFilesToMessageTask::class)->run($message->id, $dto->fileUuids);
            }

            return $message->load('files');
        });
    }

    /**
     * Удаляет файлы, открепленные от сообщения
     */
    private function deleteUnusedFiles(Collection $files, array $deletedFileUuids): void
    {
        $deletedFiles = $files->whereIn('uuid', $deletedFileUuids);
        DeleteFiles::dispatch($deletedFiles);
    }
}