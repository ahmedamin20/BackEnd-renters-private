<?php

namespace Modules\Order\Services;

use App\Exceptions\ValidationErrorsException;
use Illuminate\Support\Facades\DB;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Product\Services\ProductService;

class OrderService
{
    private Order $orderModel;

    public function __construct(Order $orderModel)
    {
        $this->orderModel = $orderModel;
    }

    public function index()
    {
        $incoming = request()->input('incoming', false);

        return $this->orderModel::query()
            ->whereMine()
            ->where($incoming ? 'from_user_id' : 'to_user_id', auth()->id())
            ->with([
                'fromUser',
                'toUser',
                'products' => function($query){
                    $query->select(['products.id', 'name', 'rating_average', 'health', 'description', 'category_id']);
                    $query->with('main_image', 'category:id,name');
                }
            ])
            ->paginatedCollection();
    }

    public function show($id)
    {
        return $this->orderModel::query()
            ->whereMine()
            ->with([
                'fromUser',
                'toUser',
                'products' => function($query){
                    $query->select(['products.id', 'name', 'rating_average', 'health', 'description', 'category_id']);
                    $query->with('main_image', 'category:id,name');
                }
            ])
            ->findOrFail($id);
    }

    /**
     * @throws ValidationErrorsException
     */
    public function store($data)
    {
        $product = (new ProductService())->productExists($data['product_id']);

        // product is renting already !
        if($product->renting_now)
        {
            throw new ValidationErrorsException(['product_id' => 'Product is renting already']);
        }

        if($product->user_id == auth()->id())
        {
            throw new ValidationErrorsException(['product_id' => 'You can not rent your own product']);
        }

        DB::transaction(function() use ($data, $product){
            $order = $this->orderModel::create($data + [
                'price' => $product->price,
                'from_user_id' => auth()->id(),
                'to_user_id' => $product->user_id,
                'status' => OrderStatusEnum::PENDING,
            ]);

            $order->products()->attach($data['product_id']);
        });
    }
}
