<?php declare(strict_types=1);

namespace Core;

use App\Repositories\AnswerRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\QuizRepository;
use App\Repositories\UserAnswerRepository;
use App\Repositories\UserRepository;
use App\Services\AnswerService;
use App\Services\QuestionService;
use App\Services\QuizService;
use App\Services\UserService;
use Core\Databases\ConnectionFactory;
use DI\Container;
use DI\ContainerBuilder;

class Dependency
{
    public function run(): Container
    {
        $builder = new ContainerBuilder();
        $container = $builder->build();

        $databaseConnection = (new ConnectionFactory())->get($_ENV['DBMS']);
        $container->set(
            'db',
            $databaseConnection::getInstance(
                $_ENV['DB_HOST'],
                $_ENV['DB_DATABASE'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD']
            )
        );

        $container->set(
            'UserService',
            function () use ($container) {
                return new UserService(new UserRepository($container->get('db')));
            }
        );

        $container->set(
            'QuizService',
            function () use ($container) {
                return new QuizService(new QuizRepository($container->get('db')));
            }
        );

        $container->set(
            'AnswerService',
            function () use ($container) {
                return new AnswerService(
                    new AnswerRepository($container->get('db')),
                    new UserAnswerRepository($container->get('db'))
                );
            }
        );

        $container->set(
            'QuestionService',
            function () use ($container) {
                return new QuestionService(new QuestionRepository($container->get('db')));
            }
        );

        return $container;
    }
}