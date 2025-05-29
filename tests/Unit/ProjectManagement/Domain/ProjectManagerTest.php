<?php

declare(strict_types=1);

namespace Freyr\TDD\Tests\Unit\ProjectManagement\Domain;

use Freyr\TDD\ProjectManagement\Domain\Admin;
use Freyr\TDD\ProjectManagement\Domain\Project;
use Freyr\TDD\ProjectManagement\Domain\ProjectId;
use Freyr\TDD\ProjectManagement\Domain\ProjectManager;
use Freyr\TDD\ProjectManagement\Domain\User;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ProjectManagerTest extends TestCase
{

    #[Test]
    public function cannotAssignToProjectIfAdminDoNotHavePermission(): void
    {
        $sut = new ProjectManager();
        $user = $sut->assignUserToProject(
            new User('', true, []),
            new Admin(['canAssignToProjects' => false]),
            new ProjectId(''),
        );

        self::assertNull($user);
    }

    #[Test]
    public function cannotAssignToProjectIfUserIsInactive(): void
    {
        $sut = new ProjectManager();
        $user = $sut->assignUserToProject(
            new User('', false, []),
            new Admin(['canAssignToProjects' => true]),
            new ProjectId(''),
        );

        self::assertNull($user);
    }

    #[Test]
    public function cannotAssignToProjectIfUserHasToManyProjects(): void
    {
        $sut = new ProjectManager();
        $user = $sut->assignUserToProject(
            new User('', true, [
                new Project(new ProjectId('1')),
                new Project(new ProjectId('2')),
                new Project(new ProjectId('3')),
                new Project(new ProjectId('4')),
            ]),
            new Admin(['canAssignToProjects' => true]),
            new ProjectId(''),
        );

        self::assertNull($user);
    }


    #[Test]
    public function canAssignToProjectIfNoRulesAreBroken(): void
    {
        $sut = new ProjectManager();
        $user = $sut->assignUserToProject(
            new User('', true, []),
            new Admin(['canAssignToProjects' => true]),
            new ProjectId('1'),
        );

        self::assertNotNull($user);
        self::assertInstanceOf(User::class, $user);
    }
}