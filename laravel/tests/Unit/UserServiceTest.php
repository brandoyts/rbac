<?php

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use App\Services\UserService;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Mockery;

beforeEach(function() {
    $this->mockRepo = Mockery::mock(UserRepositoryInterface::class);
    $this->service = new UserService($this->mockRepo);
});

test('find user by email', function () {
    $email = "test@example.com";
    $user = new User(["email" => $email, "name" => "test"]);

    $mockRepo = Mockery::mock(UserRepositoryInterface::class);
    $mockRepo->shouldReceive('findByEmail')
            ->once()
            ->with($email)
            ->andReturn($user);

    $service = new UserService($mockRepo);
    $result = $service->findByEmail($email);

    expect($result)->toBeInstanceOf(User::class)
                   ->and($result->email)->toBe($email);
});

test("find user by id", function() {
    $id = 1;

    $user =  new User(["name" => "test"]);
    $user->id = $id;

    $mockRepo = Mockery::mock(UserRepositoryInterface::class);
    $mockRepo->shouldReceive("findById")
            ->once()
            ->with($id)
            ->andReturn($user);

    $service = new UserService($mockRepo);
    $result = $service->findUserById($id);

    expect($result)->toBeInstanceOf(User::class)
                    ->and($result->id)->toBe($id);
});

test("no user found using provided id", function() {
    $id = 1;

    $mockRepo = Mockery::mock(UserRepositoryInterface::class);
    $mockRepo->shouldReceive("findById")
            ->once()
            ->with($id)
            ->andReturn(null);

    $service = new UserService($mockRepo);
    $result = $service->findUserById($id);

    expect($result)->toBeNull();
});

test("no user found using provided email", function() {
    $email = "test@mail.com";

    $mockRepo = Mockery::mock(UserRepositoryInterface::class);
    $mockRepo->shouldReceive("findByEmail")
            ->once()
            ->with($email)
            ->andReturn(null);

    $service = new UserService($mockRepo);
    $result = $service->findByEmail($email);

    expect($result)->toBeNull();
});

test("create a new user successfully", function() {
    $fields = [
        "name" => "brando",
        "email" => "brando@mail.com",
        "password" => "secret"
    ];

    $mockUser = new User($fields);

    $this->mockRepo
        ->shouldReceive("create")
        ->once()
        ->with($fields)
        ->andReturn($mockUser);

    $result = $this->service->createUser($fields);

    expect($result)->toBeInstanceOf(User::class);
    expect($result->email)->toBe($fields['email']);
});
