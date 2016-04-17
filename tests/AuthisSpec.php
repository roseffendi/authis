<?php

namespace tests\Roseffendi\Authis;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Roseffendi\Authis\User;
use Roseffendi\Authis\Resource;

class AuthisSpec extends ObjectBehavior
{
    function let(User $user)
    {
        $this->beConstructedWith($user);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Roseffendi\Authis\Authis');
    }

    function it_check_ability_of_user(User $user)
    {
        $user->abilities()->shouldBeCalled()->willReturn(['update']);

        $this->check('update')
             ->shouldEqual(true);
    }

    function it_can_map_alias(User $user)
    {
        $user->abilities()->shouldBeCalled()->willReturn(['update-get']);

        $this->alias('update-get', 'update-post')
             ->check('update-post')
             ->shouldEqual(true);
    }

    function it_can_intercept_permission(User $user)
    {
        $user->abilities()->shouldBeCalled()->willReturn(['super-update']);

        $this->intercept('update', function($user, $ability){
            return $this->check('super-update');
        })->check('update')->shouldEqual(true);
    }

    function it_can_check_for_resource_owner(User $user, Resource $resource)
    {
        $resource->isBelongsTo($user)->shouldBeCalled()->willReturn(false);
        $user->abilities()->shouldBeCalled()->willReturn(['update']);

        $this->forResource($resource)
             ->check('update')
             ->shouldEqual(false);
    }
}
