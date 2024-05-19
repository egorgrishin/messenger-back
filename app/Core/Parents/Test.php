<?php

namespace Core\Parents;

use Core\Classes\Auth\Jwt;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Event;

abstract class Test extends TestCase
{
    use LazilyRefreshDatabase;

    protected Jwt $jwt;

    public function __construct(string $name)
    {
        $this->jwt = new Jwt();
        parent::__construct($name);
    }

    /**
     * Предотвращает выполнение слушателей событий за исключением событий модели
     */
    protected function fakeEventWithModel(): void
    {
        $dispatcher = Event::getFacadeRoot();
        Event::fake();
        Model::setEventDispatcher($dispatcher);
    }
}
