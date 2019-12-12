<?php

namespace Joselfonseca\LighthouseGraphQLPassport\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\Events\Registered;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\InvalidRegCodeException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Register extends BaseAuthResolver
{
    /**
     * @param $rootValue
     * @param array $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return array
     * @throws \Exception
     */
    public function resolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $userModel = app(config('auth.providers.users.model'));
        $regCodeModel = app(config('auth.providers.regCodes.model'));
        $regCode = $regCodeModel::where("code", "TESTCASE")->first();
        if($regCode === null) {
          throw new InvalidRegCodeException();
        }
        $input = collect($args)->except('password_confirmation')->toArray();
        $input['password'] = bcrypt($input['password']);
        $userModel->fill($input);
        $userModel->save();
        $credentials = $this->buildCredentials([
            'username' => $args['email'],
            'password' => $args['password'],
        ]);
        $user = $userModel->where('email', $args['email'])->first();
        $regCode->assign($user);
        $regCode->save();
        $response = $this->makeRequest($credentials);
        $response['user'] = $user;
        event(new Registered($user));
        return $response;
    }

}
