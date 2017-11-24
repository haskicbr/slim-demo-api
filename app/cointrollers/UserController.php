<?php

namespace App\Controllers;

use App\Models\User;
use Psr\Container\ContainerInterface;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;


class UserController
{

    /**
     * UserController constructor.
     * @param ContainerInterface $ci
     */

    public function __construct(ContainerInterface $ci)
    {
        $this->ci = $ci;
        $this->validator = $this->ci['validator'];
    }

    public function validateRequest(Request $request)
    {
        $this->validator->validate($request, [
            'first_name' => [
                'rules'    => V::length(1, 255)
                    ->noWhitespace()
                    ->notBlank(),
                'messages' => [
                    'length'       => 'Длина должна быть от 3 до 255 символов без пробелов',
                    'noWhitespace' => 'Поле не должно содержать пробелы',
                    'notBlank'     => 'Поле не может быть пустым'
                ],
            ],

            'last_name' => [
                'rules'    => V::length(1, 255)
                    ->noWhitespace()
                    ->notBlank(),
                'messages' => [
                    'length'       => 'Длина должна быть от 3 до 255 символов без пробелов',
                    'noWhitespace' => 'Поле не должно содержать пробелы',
                    'notBlank'     => 'Поле не может быть пустым'
                ],
            ],

            'middle_name' => [
                'rules'    => V::length(1, 255)
                    ->noWhitespace()
                    ->notBlank(),
                'messages' => [
                    'length'       => 'Длина должна быть от 3 до 255 символов без пробелов',
                    'noWhitespace' => 'Поле не должно содержать пробелы',
                    'notBlank'     => 'Поле не может быть пустым'
                ],
            ],


            'email' => [
                'rules'    => V::email()
                    ->notBlank(),
                'messages' => [
                    'email'    => 'Поле должно являться корректным email адресом',
                    'notBlank' => 'Поле не может быть пустым'
                ],
            ],


            'phone' => [
                'rules'    => V::Numeric()
                    ->notBlank(),
                'messages' => [
                    'length'       => 'Длина должна быть от 11 символов',
                    'noWhitespace' => 'Поле не должно содержать пробелы',
                    'notBlank'     => 'Поле не может быть пустым',
                    'numeric'      => 'Поле должно содержать только числа'
                ],
            ],
        ]);

        if ($this->validator->isValid()) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed
     */

    public function get(Request $request, Response $response, $args)
    {
        return User::all()->toJson();
    }


    public function getOne(Request $request, Response $response, $args)
    {
        if (!empty($args['id'])) {

            $id = (int)$args['id'];

            $model = User::find($id);

            if (!empty($model)) {
                return $response
                    ->withStatus(200)
                    ->withJson($model->toArray());
            }
        }

        return $response->withStatus(404);
    }

    public function create(Request $request, Response $response, $args)
    {
        if ($this->validateRequest($request)) {

            $model = new User();
            $model->setRawAttributes($request->getParsedBody());
            $model->save();

            return $response->withStatus(200)->withJson([
                'id' => $model->id
            ]);
        } else {
            return $response->withStatus(400)->withJson($this->validator->getErrors());
        }
    }

    public function update(Request $request, Response $response, $args)
    {
        if (!empty($args['id'])) {
            $id = (int)$args['id'];
            $model = User::find($id);

            if (!empty($model)) {
                if ($this->validateRequest($request)) {
                    $model->setRawAttributes($request->getParsedBody());
                    $model->save();
                    return $response->withStatus(200);
                } else {
                    return $response->withStatus(400)->withJson($this->validator->getErrors());
                }
            }
        }

        return $response->withStatus(404);
    }

    public function delete(Request $request, Response $response, $args)
    {
        if (!empty($args['id'])) {

            $id = (int)$args['id'];

            $model = User::find($id);

            if (!empty($model) && $model->delete()) {
                return $response->withStatus(200);
            }
        }

        return $response->withStatus(404);
    }
}