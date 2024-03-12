<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Painting;
use App\Category;
use App\Message;
use App\Order;
use App\News;
use App\Featured;
use Validator;
use Mail;

class DefaultController extends Controller
{
    public function homePage()
    {
        $news = News::orderBy('created_at', 'desc')->get();
        $featuredArtist = Featured::where('current', 1)->firstOrFail();

        return view('page.home', [
            'news' => $news,
            'featuredArtist' => $featuredArtist,
        ]);
    }

    public function aboutPage()
    {
        return view('page.about');
    }

    public function contactPage()
    {
        return view('page.contact');
    }

    public function contact(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $message = $request->input('message');

        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'message' => $message,
        ];

        $rules = [
            'name' => [
                'required',
                'max:48',
            ],
            'email' => [
                'nullable',
                'required_if:phone,',
                'email',
            ],
            'phone' => [
                'nullable',
                'required_if:email,',
                'regex:/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/',
            ],
            'message' => [
                'required',
                'max:4096',
            ],
        ];

        $messages = [
            'name.required' => 'Enter your name',
            'name.max' => 'Name is too long, should be no more than 48 characters',
            'email.email' => 'Invalid email address',
            'email.required_if' => 'Need a mail or phone (or both)',
            'phone.regex' => 'Invalid phone number',
            'phone.required_if' => 'Need a mail or phone (or both)',
            'message.required' => 'Enter your message',
            'message.max' => 'Message too long',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {

            return Response()->json([
                'status' => false,
                'data' => [
                    'name' => $validator->errors()->first('name'),
                    'email' => $validator->errors()->first('email'),
                    'phone' => $validator->errors()->first('phone'),
                    'message' => $validator->errors()->first('message'),
                ],
            ], 200);

        } else {
            
            $messageModel = new Message();
            $messageModel->name = $name;
            $messageModel->email = $email;
            $messageModel->phone = $phone;
            $messageModel->message = $message;
            $messageModel->save();

            if (empty($email)) $email = '-';

            if (empty($phone)) $phone = '-';

            $body = $request->input('message').PHP_EOL.PHP_EOL.'Email: '.$email.PHP_EOL.PHP_EOL.'Phone: '.$phone;

            Mail::raw($body, function($message) use($name) {
                $message->from('info@ialamaart.com', $name);
                $message->to('info@ialamaart.com', $name);
            });

            return Response()->json([
                'status' => true,
            ], 200);
        }

    }

    public function termsPage()
    {
        return view('page.terms');
    }

    public function servicesPage()
    {
        return view('service.home');
    }

    public function digitalPage()
    {
        return view('service.digital');
    }

    public function digital(Request $request)
    {
        $data = [
            'category' => $request->input('category'),
            'rate' => $request->input('rate'),
            'images' => $request->input('images'),
            'email' => $request->input('email'),
            'wishes' => $request->input('wishes'),
        ];

        $rules = [
            'category' => [
                'required',
                'integer',
                'between:0,2',
            ],
            'rate' => [
                'required',
                'integer',
                'between:0,2',
            ],
            'images' => [
                'required',
                'array',
                'between:1,4',
            ],
            'email' => [
                'required',
                'email',
            ],
            'wishes' => [
                'required',
                'max:4096',
            ],
        ];

        $messages = [
            'category.required' => 'Category is empty',
            'category.between' => 'Invalid category',
            'rate.required' => 'Rate is empty',
            'rate.between' => 'Invalid rate',
            'images.required' => 'Images not found',
            'images.array' => 'Images not found',
            'images.between' => 'You can upload from 1 to 4 photos',
            'email.required' => 'Enter email',
            'email.email' => 'Invalid email address',
            'wishes.required' => 'Enter comment',
            'wishes.max' => 'Too long',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {

            if (empty($validator->errors()->first('images'))) {

                $imagesValidationResult = $this->imagesValidation($request->input('images'));

                if ($imagesValidationResult === false) {
                    $imagesError = 'One or more photos have something wrong';
                } else {
                    $imagesError = '';
                }
                
            } else {
                $imagesError = $validator->errors()->first('images');
            }

            return Response()->json([
                'status' => false,
                'data' => [
                    'category' => $validator->errors()->first('category'),
                    'rate' => $validator->errors()->first('rate'),
                    'images' => $imagesError,
                    'email' => $validator->errors()->first('email'),
                    'wishes' => $validator->errors()->first('wishes'),
                ],
            ], 200);

        } else {

            $imagesValidationResult = $this->imagesValidation($request->input('images'));

            if ($imagesValidationResult === false) {

                return Response()->json([
                    'status' => false,
                    'data' => [
                        'category' => '',
                        'rate' => '',
                        'images' => 'One or more photos have something wrong',
                        'email' => '',
                        'wishes' => '',
                    ],
                ], 200);

            } else {

                // тут сохраняем весь заказ в бд, с именами файлов и прочей информацией
                
                $currentCombination = $request->input('category').$request->input('rate');

                switch ($currentCombination) {
                    case '00': $price = 500; break;
                    case '01': $price = 800; break;
                    case '02': $price = 1000; break;
                    case '10': $price = 800; break;
                    case '11': $price = 1200; break;
                    case '12': $price = 1500; break;
                    case '20': $price = 1200; break;
                    case '21': $price = 1600; break;
                    case '22': $price = 2000; break;
                    default: break;
                }

                $data = [
                    'category' => $request->input('category'),
                    'rate' => $request->input('rate'),
                    'files' => $imagesValidationResult,
                ];

                $order = new Order();
                $order->status = 0;
                $order->type = 'digital';
                $order->price = $price;
                $order->email = $request->input('email');
                $order->comment = $request->input('wishes');
                $order->data = json_encode($data);

                $order->save();

                $body = 'The order for the digital art service was received!'.PHP_EOL.PHP_EOL.
                        'Order ID: '.$order->id.PHP_EOL.
                        'Price: $'.$order->price.PHP_EOL.
                        'Email: '.$request->input('email').PHP_EOL.PHP_EOL.
                        $request->input('wishes');

                Mail::raw($body, function($message) {
                    $message->from('info@ialamaart.com', 'Digital art');
                    $message->to('info@ialamaart.com', 'Digital art');
                });

                return Response()->json([
                    'status' => true,
                    'orderId' => $order->id
                ], 200);
                
            }

        }

    }

    /**
     * Проверяет изображения, в случае успеха возвращает имя сохраненного файла.
     *
     * @param array $files
     *
     * @return array
     */
    private function imagesValidation($files)
    {
        $availableMimeTypes = ['image/jpeg', 'image/png', 'image/bmp'];

        if (empty($files)) return false;
        
        foreach ($files as $image) {

            $image = base64_decode(explode(',', $image)[1]);
            $f = finfo_open();
            $mimeType = finfo_buffer($f, $image, FILEINFO_MIME_TYPE);

            if (!in_array($mimeType, $availableMimeTypes)) return false;

            switch ($mimeType) {

                case 'image/jpeg':
                    $extension = '.jpg';
                    break;

                case 'image/png':
                    $extension = '.png';
                    break;

                case 'image/bmp':
                    $extension = '.bmp';
                    break;

                default:
                    break;

            }

            $images[] = [
                'data' => $image,
                'extension' => $extension,
            ];

        }

        foreach ($images as $image) {
            $imagesNames[] = $this->saveImage($image['data'], $image['extension']);
        }

        return $imagesNames;
    }

    /**
     * Генерирует уникальное имя для нового изображения и сохраняет его
     * в папку storage/digital/.
     *
     * @param string $data
     * @param string $extension
     *
     * @return string
     */
    private function saveImage($data, $extension)
    {
        $path = 'storage/digital/';
        $filename = '';
        $filenameLength = 16;

        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $filenameLength; $i++) {
            $filename .= $keys[array_rand($keys)];
        }

        if (file_exists($path.$filename.$extension)) $this->saveImage($data, $extension);
        
        file_put_contents($path.$filename.$extension, $data);

        return $filename.$extension;
    }
}
