<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests\Unit\ProjectManagement\Application;

use Freyr\TDD\Notifications\NotificationService;
use Freyr\TDD\ProjectManagement\Application\UserService;
use Freyr\TDD\ProjectManagement\Domain\Admin;
use Freyr\TDD\ProjectManagement\Domain\ProjectId;
use Freyr\TDD\ProjectManagement\Domain\ProjectManager;
use Freyr\TDD\ProjectManagement\Domain\User;
use Freyr\TDD\ProjectManagement\Domain\UserRepository;
use Freyr\TDD\Tests\Unit\ProjectManagement\NotificationInMemoryService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private MockObject&UserRepository $userRepository;
    private MockObject&NotificationService $notificationService;
    private ProjectManager&MockObject $projectManager;

    protected function setUp(): void
    {
        $this->userRepository = $this
            ->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->projectManager = $this->getMockBuilder(ProjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->notificationService = $this
            ->getMockBuilder(NotificationService::class)
            ->disableOriginalConstructor()->getMock();
    }

    #[Test]
    public function shouldNotAssignIfUserDoesNotExists(): void
    {
        # given
        $sut = new UserService($this->userRepository, $this->projectManager, $this->notificationService);

        #when
        $this->userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->willReturn(null);

        $status = $sut->assignUserToProject('1', new Admin([]), new ProjectId(''));

        #then
        self::assertFalse($status);
    }

    #[Test]
    public function shouldNotTriggerNotificationWhenUserExistsButCannotBeAssigned(): void
    {
        # given
        $sut = new UserService($this->userRepository, $this->projectManager, $this->notificationService);

        #when
        $this->userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->willReturn(new User('', true, []));

        $this->projectManager
            ->expects($this->once())
            ->method('assignUserToProject')
            ->willReturn(null);

        $this->notificationService
            ->expects($this->never())
            ->method('sendNotification');

        $status = $sut->assignUserToProject('1', new Admin([]), new ProjectId(''));

        #then
        self::assertFalse($status);
    }

    #[Test]
    public function shouldNotifyWithProperMessageStructure(): void
    {
        # given
        $notificationService = new NotificationInMemoryService();
        $sut = new UserService($this->userRepository, $this->projectManager, $notificationService);

        #when
        $user = new User('', true, []);
        $this->userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->willReturn($user);

        $this->projectManager
            ->expects($this->once())
            ->method('assignUserToProject')
            ->willReturn($user);


        $status = $sut->assignUserToProject('1', new Admin([]), new ProjectId(''));
        $json = $notificationService->notifications[0];
        self::assertSame('[]', $json);

        #then
        self::assertTrue($status);
    }
}