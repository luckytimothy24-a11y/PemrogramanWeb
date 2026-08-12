<?php

namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\StockOpnameRepositoryInterface;
use Illuminate\Support\Facades\DB;

class StockOpnameService
{
    protected $opnameRepository;
    protected $productRepository;
    protected $activityLogService;

    public function __construct(
        StockOpnameRepositoryInterface $opnameRepository,
        ProductRepositoryInterface $productRepository,
        ActivityLogService $activityLogService
    ) {
        $this->opnameRepository = $opnameRepository;
        $this->productRepository = $productRepository;
        $this->activityLogService = $activityLogService;
    }

    public function getAll(array $filters = [])
    {
        return $this->opnameRepository->getAll($filters);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = $this->productRepository->findById($data['product_id']);
            $systemQty = $product->stock;
            $actualQty = (int) $data['actual_qty'];
            $difference = $actualQty - $systemQty;

            $opname = $this->opnameRepository->create([
                'product_id' => $product->id,
                'system_qty' => $systemQty,
                'actual_qty' => $actualQty,
                'difference' => $difference,
                'note' => $data['note'] ?? null,
                'user_id' => auth()->id(),
                'opname_date' => $data['opname_date'] ?? now(),
            ]);

            $this->productRepository->update($product->id, [
                'stock' => $actualQty,
            ]);

            $this->activityLogService->log(
                'Stock Opname',
                "Opname \"{$product->name}\": sistem {$systemQty}, aktual {$actualQty} (selisih {$difference})"
            );

            return $opname;
        });
    }

    public function delete($id)
    {
        return $this->opnameRepository->delete($id);
    }
}
