<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Painting;
use App\Category;
use App\Message;
use App\Order;
use App\News;
use App\Featured;
use Illuminate\Support\Facades\Log;
use Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboardPage()
    {
        return view('admin.dashboard', [
            'paintingsCount' => Painting::count(),
            'ordersCount' => Order::count(),
            'eventsCount' => News::count(),
            'featuredCount' => Featured::count(),
            'breadcrumbs' => [
                ['href' => '', 'name' => 'Dashboard'],
            ],
        ]);
    }

    public function paintingsPage()
    {
        return view('admin.painting.all', [
            'paintings' => Painting::orderBy('created_at', 'desc')->get(),
            'breadcrumbs' => [
                ['href' => route('admin::dashboard'), 'name' => 'Dashboard'],
                ['href' => '', 'name' => 'Paintings'],
            ],
        ]);
    }

    public function addPaintingPage()
    {
        $categoriesList = Category::all();
        $sizes = ['s', 'm', 'l', 'xl'];
        $orientations = ['square', 'portrait', 'landscape', 'elliptic'];

        return view('admin.painting.add', [
            'photoCount' => config('app.painting_form_photo_count'),
            'categoriesList' => $categoriesList,
            'sizes' => $sizes,
            'orientations' => $orientations,
            'breadcrumbs' => [
                ['href' => route('admin::dashboard'), 'name' => 'Dashboard'],
                ['href' => route('admin::paintings'), 'name' => 'Paintings'],
                ['href' => '', 'name' => 'Add painting'],
            ],
        ]);
    }

    public function addPainting(Request $request)
    {
        $images = $request->input('images');
        $imagesError = $this->paintingImagesValidation($images);

        $data = [
            'name' => $request->input('name'),
            'link' => $request->input('link'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'category' => $request->input('category'),
            'size' => $request->input('size'),
            'orientation' => $request->input('orientation'),
        ];

        $rules = [
            'name' => [
                'required',
                'max:48',
            ],
            'link' => [
                'required',
                'unique:paintings',
                'max:48',
            ],
            'price' => [
                'required',
                'integer',
            ],
            'description' => [
                'required',
                'max:4096',
            ],
            'category' => [
                'required',
                'exists:categories,id',
            ],
            'size' => [
                'required',
                Rule::in(['s', 'm', 'l', 'xl']),
            ],
            'orientation' => [
                'required',
                Rule::in(['square', 'portrait', 'landscape', 'elliptic']),
            ],
        ];

        $messages = [
            'name.required' => 'Enter painting name',
            'name.max' => 'The name can not exceed 48 characters in length',
            'link.required' => 'Enter painting link',
            'link.unique' => 'The link must be unique',
            'link.max' => 'Link length must not exceed 48 characters',
            'price.required' => 'Enter painting price',
            'price.integer' => 'You must enter numbers',
            'description.required' => 'Enter painting description',
            'description.max' => 'Painting description too long',
            'category.required' => 'Category not selected',
            'category.exists' => 'Invalid category',
            'size.required' => 'Size required',
            'size.in' => 'Invalid size',
            'orientation.required' => 'Orientation required',
            'orientation.in' => 'Invalid orientation',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {

            if (true === $imagesError) $imagesError = '';
            
            return Response()->json([
                'status' => false,
                'data' => [
                    'images' => $imagesError,
                    'name' => $validator->errors()->first('name'),
                    'link' => $validator->errors()->first('link'),
                    'price' => $validator->errors()->first('price'),
                    'description' => $validator->errors()->first('description'),
                    'category' => $validator->errors()->first('category'),
                    'size' => $validator->errors()->first('size'),
                    'orientation' => $validator->errors()->first('orientation'),
                ],
            ], 200);

        } else {

            if (true !== $imagesError) {

                return Response()->json([
                    'status' => false,
                    'data' => [
                        'images' => $imagesError,
                        'name' => '',
                        'link' => '',
                        'price' => '',
                        'description' => '',
                        'category' => '',
                        'size' => '',
                        'orientation' => '',
                    ],
                ], 200);

            } else {

                $painting = new Painting();
                $painting->name = $request->input('name');
                $painting->link = $request->input('link');
                $painting->price = $request->input('price');
                $painting->description = $request->input('description');
                $painting->category = $request->input('category');
                $painting->size = $request->input('size');
                $painting->orientation = $request->input('orientation');
                $painting->save();

                Storage::makeDirectory('/public/paintings/'.$painting->id);

                for ($i = 0; $i < count($images); ++$i) {
                    if (null !== $images[$i]) {
                        $image = base64_decode(explode(',', explode(';', $images[$i])[1])[1]);
                        file_put_contents('storage/paintings/'.$painting->id.'/'.$i.'.jpg', $image);
                    }
                }

                return Response()->json([
                    'status' => true,
                    'data' => [
                        'imageLink' => route('painting::painting', ['link' => $painting->link]),
                    ],
                ], 200);

            }
            
        }
    }

    public function editPaintingPage($link)
    {
        $painting = Painting::where('link', $link)->firstOrFail();
        $categoriesList = Category::all();
        $sizes = ['s', 'm', 'l', 'xl'];
        $orientations = ['square', 'portrait', 'landscape', 'elliptic'];

        return view('admin.painting.edit', [
            'photoCount' => config('app.painting_form_photo_count'),
            'painting' => $painting,
            'categoriesList' => $categoriesList,
            'sizes' => $sizes,
            'orientations' => $orientations,
            'breadcrumbs' => [
                ['href' => route('admin::dashboard'), 'name' => 'Dashboard'],
                ['href' => route('admin::paintings'), 'name' => 'Paintings'],
                ['href' => '', 'name' => $painting->name],
            ],
        ]);
    }

    public function editPainting(Request $request)
    {
        $id = $request->input('id');
        $images = $request->input('images');
        $imagesError = $this->paintingImagesValidation($images);

        $data = [
            'name' => $request->input('name'),
            'link' => $request->input('link'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'category' => $request->input('category'),
            'size' => $request->input('size'),
            'orientation' => $request->input('orientation'),
        ];

        $rules = [
            'name' => [
                'required',
                'max:48',
            ],
            'link' => [
                'required',
                'unique:paintings,link,'.$id,
                'max:48',
            ],
            'price' => [
                'required',
                'integer',
            ],
            'description' => [
                'required',
                'max:4096',
            ],
            'category' => [
                'required',
                'exists:categories,id',
            ],
            'size' => [
                'required',
                Rule::in(['s', 'm', 'l', 'xl']),
            ],
            'orientation' => [
                'required',
                Rule::in(['square', 'portrait', 'landscape', 'elliptic']),
            ],
        ];

        $messages = [
            'name.required' => 'Enter painting name',
            'name.max' => 'The name can not exceed 48 characters in length',
            'link.required' => 'Enter painting link',
            'link.unique' => 'The link must be unique',
            'link.max' => 'Link length must not exceed 48 characters',
            'price.required' => 'Enter painting price',
            'price.integer' => 'You must enter numbers',
            'description.required' => 'Enter painting description',
            'description.max' => 'Painting description too long',
            'category.required' => 'Category not selected',
            'category.exists' => 'Invalid category',
            'size.required' => 'Size required',
            'size.in' => 'Invalid size',
            'orientation.required' => 'Orientation required',
            'orientation.in' => 'Invalid orientation',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {

            if (true === $imagesError) $imagesError = '';

            return Response()->json([
                'status' => false,
                'data' => [
                    'images' => $imagesError,
                    'name' => $validator->errors()->first('name'),
                    'link' => $validator->errors()->first('link'),
                    'price' => $validator->errors()->first('price'),
                    'description' => $validator->errors()->first('description'),
                    'category' => $validator->errors()->first('category'),
                    'size' => $validator->errors()->first('size'),
                    'orientation' => $validator->errors()->first('orientation'),
                ],
            ], 200);

        } else {

            if (true === $imagesError) {

                $painting = Painting::find($id);
                $painting->name = $request->input('name');
                $painting->link = $request->input('link');
                $painting->price = $request->input('price');
                $painting->description = $request->input('description');
                $painting->category = $request->input('category');
                $painting->size = $request->input('size');
                $painting->orientation = $request->input('orientation');
                $painting->save();

                for ($i = 0; $i < count($images); ++$i) {
                    if (null !== $images[$i] && !filter_var($images[$i], FILTER_VALIDATE_URL)) {
                        $image = base64_decode(explode(',', explode(';', $images[$i])[1])[1]);
                        file_put_contents('storage/paintings/'.$painting->id.'/'.$i.'.jpg', $image);
                    } elseif (null === $images[$i]) {
                        Storage::delete('/public/paintings/'.$painting->id.'/'.$i.'.jpg');
                    }
                }
                
                return Response()->json([
                    'status' => true,
                ], 200);

            } else {

                return Response()->json([
                    'status' => false,
                    'data' => [
                        'images' => $imagesError,
                        'name' => '',
                        'link' => '',
                        'price' => '',
                        'description' => '',
                        'category' => '',
                        'size' => '',
                        'orientation' => '',
                    ],
                ], 200);

            }

        }

        return Response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    private function paintingImagesValidation($images)
    {
        $errors = false;

        foreach ($images as $image) {

            if (null != $image && !filter_var($image, FILTER_VALIDATE_URL)) {

                $file = base64_decode(explode(',', explode(';', $image)[1])[1]);
                $f = finfo_open();
                $mimeType = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);

                if ($mimeType != 'image/jpeg') {
                    $errors = true;
                }

            }

        }

        if ($errors) {
            return "Something wrong with images (need jpeg)";
        } else {
            return true;
        }
    }

    public function setAvailablePainting(Request $request)
    {
        try {

            $painting = Painting::find($request->input('id'));
            $painting->availability = 1;
            $painting->save();

            return Response()->json([
                'status' => true,
            ]);

        } catch (Exception $e) {

            return Response()->json([
                'status' => false,
                'data' => [
                    'error' => $e->getMessage(),
                ],
            ]);

        }
    }

    public function deletePainting(Request $request)
    {
        try {

            $painting = Painting::find($request->input('id'));
            $painting->delete();

            return Response()->json([
                'status' => true,
            ]);

        } catch (Exception $e) {

            return Response()->json([
                'status' => false,
                'data' => [
                    'error' => $e->getMessage(),
                ],
            ]);

        }
    }

    public function ordersPage()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();

        return view('admin.order.all', [
            'breadcrumbs' => [
                ['href' => route('admin::dashboard'), 'name' => 'Dashboard'],
                ['href' => '', 'name' => 'Orders'],
            ],
            'orders' => $orders,
        ]);
    }

    public function orderPage($id)
    {
        $order = Order::where('id', $id)->firstOrFail();

        switch ($order->type) {

            case 'painting':

                $orderData = json_decode($order->data, true);
                $paintings = Painting::whereIn('id', $orderData['ids'])->get();

                return view('admin.order.painting', [
                    'breadcrumbs' => [
                        ['href' => route('admin::dashboard'), 'name' => 'Dashboard'],
                        ['href' => route('admin::orders'), 'name' => 'Orders'],
                        ['href' => '', 'name' => '#'.$id],
                    ],
                    'order' => $order,
                    'orderData' => $orderData,
                    'paintings' => $paintings,
                ]);

                break;
            
            case 'digital':

                $data = json_decode($order->data, true);

                $categories = [
                    0 => 'Self portrait packages',
                    1 => 'Couple portrait packages',
                    2 => 'Family portrait packages',
                ];

                $rates = [
                    0 => 'Classic',
                    1 => 'Gold',
                    2 => 'Platinum',
                ];

                return view('admin.order.digital', [
                    'breadcrumbs' => [
                        ['href' => route('admin::dashboard'), 'name' => 'Dashboard'],
                        ['href' => route('admin::orders'), 'name' => 'Orders'],
                        ['href' => '', 'name' => '#'.$id],
                    ],
                    'order' => $order,
                    'category' => $categories[$data['category']],
                    'rate' => $rates[$data['rate']],
                    'images' => $data['files'],
                ]);

                break;

        }
    }

    public function deleteOrder(Request $request)
    {
        try {

            $order = Order::find($request->input('id'));
            $order->delete();

            return Response()->json([
                'status' => true,
            ]);

        } catch (Exception $e) {

            return Response()->json([
                'status' => false,
                'data' => [
                    'error' => $e->getMessage(),
                ],
            ]);

        }
    }

    public function eventsPage()
    {
        return view('admin.event.all', [
            'breadcrumbs' => [
                ['href' => route('admin::dashboard'), 'name' => 'Dashboard'],
                ['href' => '', 'name' => 'Events'],
            ],
            'news' => News::orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function editEventPage($id)
    {
        $event = News::find($id);

        return view('admin.event.edit', [
            'breadcrumbs' => [
                ['href' => route('admin::dashboard'), 'name' => 'Dashboard'],
                ['href' => route('admin::events'), 'name' => 'Events'],
                ['href' => '', 'name' => '#'.$event->id],
            ],
            'event' => $event,
        ]);
    }

    public function addEventPage()
    {
        return view('admin.event.add', [
            'breadcrumbs' => [
                ['href' => route('admin::dashboard'), 'name' => 'Dashboard'],
                ['href' => route('admin::events'), 'name' => 'Events'],
                ['href' => '', 'name' => 'Add event'],
            ],
        ]);
    }

    public function addEvent(Request $request)
    {
        $data = [
            'date' => $request->input('date'),
            'text' => $request->input('text'),
        ];

        $rules = [
            'date' => [
                'required',
            ],
            'text' => [
                'required',
                'max:4096',
            ],
        ];

        $messages = [
            'date.required' => 'Enter the date',
            'text.required' => 'Enter the text',
            'text.max' => 'The text is too long',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {

            return Response()->json([
                'status' => false,
                'data' => [
                    'date' => $validator->errors()->first('date'),
                    'text' => $validator->errors()->first('text'),
                ],
            ], 200);

        } else {

            $event = new News();
            $event->created_at = $data['date'];
            $event->updated_at = $data['date'];
            $event->text = $data['text'];
            $event->save();

            return Response()->json([
                'status' => true,
                'data' => '',
            ]);
        }
    }

    public function editEvent(Request $request)
    {
        $data = [
            'date' => $request->input('date'),
            'text' => $request->input('text'),
        ];

        $rules = [
            'date' => [
                'required',
            ],
            'text' => [
                'required',
                'max:4096',
            ],
        ];

        $messages = [
            'date.required' => 'Enter the date',
            'text.required' => 'Enter the text',
            'text.max' => 'The text is too long',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {

            return Response()->json([
                'status' => false,
                'data' => [
                    'date' => $validator->errors()->first('date'),
                    'text' => $validator->errors()->first('text'),
                ],
            ], 200);

        } else {

            $event = News::find($request->input('id'));
            $event->created_at = $data['date'];
            $event->updated_at = $data['date'];
            $event->text = $data['text'];
            $event->save();

            return Response()->json([
                'status' => true,
                'data' => '',
            ]);
        }
    }

    public function deleteEvent(Request $request)
    {
        try {

            $event = News::find($request->input('id'));
            $event->delete();

            return Response()->json([
                'status' => true,
            ]);

        } catch (Exception $e) {

            return Response()->json([
                'status' => false,
                'data' => [
                    'error' => $e->getMessage(),
                ],
            ]);

        }
    }

    public function featuredPage()
    {
        return view('admin.featured.all', [
            'breadcrumbs' => [
                ['href' => route('admin::dashboard'), 'name' => 'Dashboard'],
                ['href' => '', 'name' => 'Featured artists'],
            ],
            'featured' => Featured::orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function addFeaturedPage()
    {
        return view('admin.featured.add', [
            'breadcrumbs' => [
                ['href' => route('admin::dashboard'), 'name' => 'Dashboard'],
                ['href' => route('admin::featured'), 'name' => 'Featured artists'],
                ['href' => '', 'name' => 'Add featured artist'],
            ],
        ]);
    }

    public function addFeatured(Request $request)
    {
        $imagesError = $this->addFeaturedImagesValidation($request->input('images'));

        $data = [
            'artist' => $request->input('artist'),
        ];

        $rules = [
            'artist' => [
                'required',
                'max:64',
            ],
        ];

        $messages = [
            'artist.required' => 'Enter artist name',
            'artist.max' => 'The artist name can not exceed 64 characters in length',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {

            if (true === $imagesError) $imagesError = '';

            return Response()->json([
                'status' => false,
                'data' => [
                    'artist' => $validator->errors()->first('artist'),
                    'images' => $imagesError,
                ],
            ], 200);

        } else {

            if (true !== $imagesError) {

                return Response()->json([
                    'status' => false,
                    'data' => [
                        'artist' => '',
                        'images' => $imagesError,
                    ],
                ], 200);

            } else {

                $featured = new Featured();
                $featured->artist = $request->input('artist');
                $featured->save();

                Storage::makeDirectory('/public/featured/'.$featured->id);

                for ($i = 0; $i < count($request->input('images')); $i++) {
                    if (null !== $request->input('images')[$i]) {
                        $image = base64_decode(explode(',', explode(';', $request->input('images')[$i])[1])[1]);
                        $imageName = $i + 1;
                        file_put_contents('storage/featured/'.$featured->id.'/'.$imageName.'.jpg', $image);
                    }
                }

                return Response()->json(['status' => true], 200);

            }
            
        }
    }

    private function addFeaturedImagesValidation($images)
    {
        if (null === $images[0] || null === $images[1] || null === $images[2] || null === $images[3]) return "Need 4 pictures";

        $errors = false;

        foreach ($images as $image) {

            if ($image !== null) {

                $file = base64_decode(explode(',', explode(';', $image)[1])[1]);
                $f = finfo_open();
                $mimeType = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);

                if ($mimeType != 'image/jpeg') {
                    $errors = true;
                }

            }

        }

        if ($errors) {
            return "Something wrong with images (need jpeg)";
        } else {
            return true;
        }
    }

    public function deleteFeatured(Request $request)
    {
        try {
            $featured = Featured::find($request->input('id'));
            $featured->delete();
            return Response()->json([
                'status' => true,
            ]);
        } catch (Exception $e) {
            return Response()->json([
                'status' => false,
                'data' => [
                    'error' => $e->getMessage(),
                ],
            ]);
        }
    }

    public function updateCurrentFeatured(Request $request)
    {
        $id = $request->input('id');

        $featuredAll = Featured::all();

        foreach ($featuredAll as $featuredItem) {
            $featuredItem->current = 0;
            $featuredItem->save();
        }

        $current = Featured::find($id);
        $current->current = 1;
        $current->save();

        return Response()->json([
            'status' => true,
            'data' => [
                'id' => $id,
            ],
        ]);
    }
}