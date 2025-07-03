<?php

class RegisterUserRequest
{
    public function __construct(public string $email, public string $password, public string $confirmPassword) {}
}

class User
{
    public function __construct(private string $uuid, private string $email, private string $hashedPassword) {}
}

interface PasswordHasherInterface
{
    public function hash(string $plain): string;
}

class BcryptPasswordHasher implements PasswordHasherInterface
{
    public function hash(string $plain): string
    {
        return password_hash($plain, PASSWORD_BCRYPT);
    }
}

class RegisterUserService
{
    public function __construct(private PasswordHasherInterface $hasher) {}

    public function register(RegisterUserRequest $userRequest): User
    {
        if ($userRequest->password !== $userRequest->confirmPassword) {
            throw new InvalidArgumentException("Passwords do not match.");
        }

        $hashedPassword = $this->hasher->hash($userRequest->password);
        $uuid = uniqid('user_', true);

        return new User($uuid, $userRequest->email, $hashedPassword);
    }
}

$hasher = new BcryptPasswordHasher();
$service = new RegisterUserService($hasher);
$request = new RegisterUserRequest("qwer", "1234", "1234");

$user = $service->register($request);

print_r($user);
