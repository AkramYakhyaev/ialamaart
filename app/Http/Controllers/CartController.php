<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Painting;
use Session;

class CartController extends Controller
{
    public function cartPage()
    {
        $items = Session::get('cart');
        $summary = 0;

        if ($items) {
            $itemsCount = count($items);
            foreach ($items as $item) {
                $summary += $item['price'];
            }
        } else {
            $itemsCount = 0;
        }

        $cartIsEmpty = $itemsCount < 1 ? true : false;
        
        return view('cart.home', [
            'cartIsEmpty' => $cartIsEmpty,
            'items' => $items,
            'summary' => $summary,
            'stripePublishableKey' => config('app.stripe_publishable_key'),
        ]);
    }

    public function cartCount(Request $request)
    {
        $items = Session::get('cart');

        return Response()->json(count($items));
    }

    public function cartManagePainting(Request $request)
    {
        $paintingId = $request->input('id');
        $action = $request->input('action');
        $painting = Painting::find($paintingId);

        if (!$painting) return Response()->json([], 404);

        if ($action == 'add') {

            $newItem = [
                'id' => $painting->id,
                'link' => $painting->link,
                'name' => $painting->name,
                'price' => $painting->price,
                'type' => 'Painting',
            ];

            Session::push('cart', $newItem);

            $response = Session::get('cart');
            return Response()->json($response);

        } elseif ($action == 'remove') {

            $items = Session::get('cart');

            for ($i = 0; $i < count($items); ++$i) {
                if ($items[$i]['id'] == $painting->id) {
                    unset($items[$i]);
                }
            }

            $newItemsArray = array_values($items);

            Session::put('cart', $newItemsArray);

            return Response()->json($request);

        } else {
            return Response()->json([], 400);
        }
    }
}
