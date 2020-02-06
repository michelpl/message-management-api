<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;

class MessageController extends Controller
{
    protected $message;
    protected $messageValidationRules = [
        'subject' => 'required|max:255|string',
        'content' => 'required|max:500|string',
        'status' => 'required|string',
        'user_id' => 'required|integer'
    ];

    public function __construct(Message $message) {
        $this->message = $message;
    }

    public function index()
    {
        return $this->message->paginate(10);
    }

    public function listByUserId($id)
    {
        return $this->message->where('user_id', "=", $id)->paginate(10);
    }

    public function show($id)
    {
        try {
            $this->message = $this->message->find($id);

            if (!$this->message) {
                throw new \Exception(
                    'Message id not found: ' . $id,
                    404
                );
            }

            return $this->message;

        } catch (\Exception $exception) {
            return $this->returnResponseError($exception);
        }
    }

    public function destroy($id)
    {
        try {
            $this->message = $this->message->find($id);

            if (!$this->message) {
                throw new \Exception(
                    'Message id not found: ' . $id,
                    404
                );
            }

            $this->message->status = 'DELETED';
            $this->message->save();

            return response($this->message, 200);

        } catch (\Exception $exception) {
            return $this->returnResponseError($exception);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $request->validate($this->messageValidationRules);

            $this->message = $this->message->find($id);

            if (!$this->message) {
                throw new \Exception(
                    'Message id not found: ' . $id,
                    404
                );
            }

            $this->message->fill($request->all());
            $this->message->save();

            return response($this->message, 200);

        } catch (\Exception $exception) {
            return $this->returnResponseError($exception);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate($this->messageValidationRules);

            $this->message->fill($request->all());
            $this->message->save();

            return response($this->message, 201);

        } catch (\Exception $exception) {
            return $this->returnResponseError($exception);
        }
    }

    private function returnResponseError(\Exception $exception)
    {
        $message = $exception->getMessage();
        $code = $exception->getCode();

        if (isset($exception->validator)) {
            $validator = $exception->validator;
            $message = [
                'message' => $exception->getMessage(),
                $validator->messages()
            ];

            $code = 400;
        }
        return response($message, $code);
    }
}
