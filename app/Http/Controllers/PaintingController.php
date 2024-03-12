<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Painting;
use Session;
use Storage;

class PaintingController extends Controller
{
    const PAINTINGS_PER_PAGE = 18;

    public function paintingsPage(Request $request)
    {
        if ($request->input('page') && is_numeric($request->input('page'))) {
            if ($request->input('page') < 2) {
                $pageExists = false;
            } else {
                $pageExists = true;
            }
        } else {
            $pageExists = false;
        }
        $page = $pageExists ? $request->input('page') : 1;

        $allCategories = Category::orderBy('name')->get();

        foreach ($allCategories as $value) {
            $categoryLinks[] = $value->link;
        }

        $pricesForView = [
            '500' => 'Up to $500',
            '500-1000' => '$500 - $1000',
            '1000-2000' => '$1000 - $2000',
            '2000-5000' => '$2000 - $5000',
            '5000-10000' => '$5000 - $10000',
            '10000' => 'Over $10000',
        ];

        $prices = [
            '500',
            '500-1000',
            '1000-2000',
            '2000-5000',
            '5000-10000',
            '10000',
        ];

        $sizesForView = [
            's' => [
                'title' => 'The longest side is up to 20 inches',
            ],
            'm' => [
                'title' => 'The longest side is up to 38 inches',
            ],
            'l' => [
                'title' => 'The longest side is up to 60 inches',
            ],
            'xl' => [
                'title' => 'The longest side is over 60 inches',
            ],
        ];

        $sizes = ['s', 'm', 'l', 'xl'];

        $orientations = ['square', 'portrait', 'landscape', 'elliptic'];

        $orientationsForView = [
            'square' => [
                'title' => 'Square',
            ],
            'portrait' => [
                'title' => 'Portrait',
            ],
            'landscape' => [
                'title' => 'Landscape',
            ],
            'elliptic' => [
                'title' => 'Elliptic'
            ]
        ];
        
        $currentCategory = $request->query('category');
        if (!$currentCategory) {
            $currentCategory = false;
        } elseif (!in_array($currentCategory, $categoryLinks)) {
            return Response()->view('error.pageNotFound', [], 404);
        }

        $currentPrice = $request->query('price');
        if (!$currentPrice) {
            $currentPrice = false;
        } elseif (!in_array($currentPrice, $prices)) {
            return Response()->view('error.pageNotFound', [], 404);
        }

        $currentSize = $request->query('size');
        if (!$currentSize) {
            $currentSize = false;
        } elseif (!in_array($currentSize, $sizes)) {
            return Response()->view('error.pageNotFound', [], 404);
        }

        $currentOrientation = $request->query('orientation');
        if (!$currentOrientation) {
            $currentOrientation = false;
        } elseif (!in_array($currentOrientation, $orientations)) {
            return Response()->view('error.pageNotFound', [], 404);
        }

        if (!$currentCategory && !$currentPrice && !$currentSize && !$currentOrientation) {

            $paintingsTotal = Painting::count();

            if ($pageExists) {
                if ($page > ceil($paintingsTotal / self::PAINTINGS_PER_PAGE)) {
                    return Response()->view('error.paintingsNotFound', [], 404);
                } else {
                    $paintings = Painting::orderBy('created_at', 'desc')->where('availability', 1)->paginate(self::PAINTINGS_PER_PAGE);
                }
            } else {
                $paintings = Painting::orderBy('created_at', 'desc')->where('availability', 1)->take(self::PAINTINGS_PER_PAGE)->get();
            }
            
        } else {

            if ($pageExists) {
                $paintingIds = $this->getIdsByFilters(
                    $allCategories,
                    $currentCategory,
                    $currentPrice,
                    $currentSize,
                    $currentOrientation,
                    $page
                );
            } else {
                $paintingIds = $this->getIdsByFilters(
                    $allCategories,
                    $currentCategory,
                    $currentPrice,
                    $currentSize,
                    $currentOrientation
                );
            }

            $paintingsTotal = count($paintingIds);
            if ($paintingIds) {
                if ($page > ceil($paintingsTotal / self::PAINTINGS_PER_PAGE)) {
                    return Response()->view('error.paintingsNotFound', [], 404);
                } else {
                    $paintings = Painting::orderBy('created_at', 'desc')->whereIn('id', $paintingIds)->paginate(self::PAINTINGS_PER_PAGE);
                }
            } else {
                return Response()->view('error.paintingsNotFound', [], 404);
            }

        }

        $needPager = $paintingsTotal > self::PAINTINGS_PER_PAGE ? true : false;

        return view('painting.home', [
            'paintings' => $paintings,
            'total' => $paintingsTotal,
            'paintingsPerPage' => self::PAINTINGS_PER_PAGE,
            'page' => $page,
            'needPager' => $needPager,
            'categories' => $allCategories,
            'category' => $currentCategory,
            'prices' => $pricesForView,
            'price' => $currentPrice,
            'sizes' => $sizesForView,
            'size' => $currentSize,
            'orientations' => $orientationsForView,
            'orientation' => $currentOrientation,
        ]);
    }

    private function getIdsByFilters($allCategories, $category, $price, $size, $orientation, $page = 1)
    {
        if ($category) {

            $categoriesCount = count($allCategories);

            for ($i = 0; $i < $categoriesCount; ++$i) {
                if ($category === $allCategories[$i]->link) {
                    $categoryId = $allCategories[$i]->id;
                    break;
                }
            }

            $categoryWhere = "AND category = $categoryId";
            
        } else {
            $categoryWhere = "";
        }

        if ($price) {
            $explodedPrice = explode('-', $price);
            $min = $explodedPrice[0];
            if (isset($explodedPrice[1])) {
                $max = $explodedPrice[1];
            }
            if (isset($max)) {
                $priceWhere = "AND price >= $min AND price < $max";
            } elseif($min <= 500) {
                $priceWhere = "AND price < $min";
            } else {
                $priceWhere = "AND price >= $min";
            }
        } else {
            $priceWhere = "";
        }

        $sizeWhere = $size ? "AND size = '$size'" : "";
        $orientationWhere = $orientation ? "AND orientation = '$orientation'" : "";

        $query = "SELECT id FROM paintings WHERE availability = 1 ".$categoryWhere." ".$priceWhere." ".$sizeWhere." ".$orientationWhere;

        $result = \DB::select($query);

        if ($result) {
            foreach ($result as $resultItem) {
                $ids[] = json_decode(json_encode($resultItem->id, true));
            }
            return $ids;
        } else {
            return false;
        }
    }

    public function paintingPage($link)
    {
        $painting = Painting::where('link', $link)->where('availability', 1)->firstOrFail();

        $items = Session::get('cart');

        $added = false;

        for ($i = 0; $i < count($items); ++$i) {
            if ($items[$i]['id'] == $painting->id) $added = true;
        }

        $images = Storage::files('public/paintings/'.$painting->id);

        foreach ($images as $image) {

            $explodedPath = explode('/', $image);
            array_shift($explodedPath);
            $newImages[] = implode('/', $explodedPath);
            
        }

        return view('painting.painting', [
            'painting' => $painting,
            'addedToCart' => $added,
            'images' => $newImages,
        ]);
    }
}
