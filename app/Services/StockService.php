<?php

namespace App\Services;

use App\Models\StockTransaction;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\StockTransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    protected $transactionRepository;
    protected $productRepository;
    protected $activityLogService;

    public function __construct(
        StockTransactionRepositoryInterface $transactionRepository,
        ProductRepositoryInterface $productRepository,
        ActivityLogService $activityLogService
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->productRepository = $productRepository;
        $this->activityLogService = $activityLogService;
    }

    public function getAllTransactions(array $filters = [])
    {
        return $this->transactionRepository->getAll($filters);
    }

    public function recordStockIn(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = $this->productRepository->findById($data['product_id']);

            $transaction = $this->transactionRepository->create([
                'product_id' => $product->id,
                'type' => StockTransaction::TYPE_IN,
                'quantity' => $data['quantity'],
                'price' => $data['price'] ?? $product->purchase_price,
                'supplier_id' => $data['supplier_id'] ?? null,
                'user_id' => auth()->id(),
                'note' => $data['note'] ?? null,
                'transaction_date' => $data['transaction_date'] ?? now(),
            ]);

            $this->productRepository->update($product->id, [
                'stock' => $product->stock + (int) $data['quantity'],
            ]);

            $this->activityLogService->log('Barang Masuk', "Penerimaan {$data['quantity']} unit \"{$product->name}\"");

            return $transaction;
        });
    }

    public function recordStockOut(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = $this->productRepository->findById($data['product_id']);

            if ((int) $data['quantity'] > $product->stock) {
                throw ValidationException::withMessages([
                    'quantity' => "Stok \"{$product->name}\" tidak mencukupi (tersedia: {$product->stock}).",
                ]);
            }

            $transaction = $this->transactionRepository->create([
                'product_id' => $product->id,
                'type' => StockTransaction::TYPE_OUT,
                'quantity' => $data['quantity'],
                'price' => $data['price'] ?? $product->selling_price,
                'supplier_id' => null,
                'user_id' => auth()->id(),
                'note' => $data['note'] ?? null,
                'transaction_date' => $data['transaction_date'] ?? now(),
            ]);

            $this->productRepository->update($product->id, [
                'stock' => max(0, $product->stock - (int) $data['quantity']),
            ]);

            $this->activityLogService->log('Barang Keluar', "Pengeluaran {$data['quantity']} unit \"{$product->name}\"");

            return $transaction;
        });
    }

    public function deleteTransaction($id)
    {
        $transaction = $this->transactionRepository->findById($id);
        $product = $transaction->product;

        $result = DB::transaction(function () use ($transaction, $product) {
            $delta = $transaction->type === StockTransaction::TYPE_IN
                ? -$transaction->quantity
                : $transaction->quantity;

            $this->productRepository->update($product->id, [
                'stock' => max(0, $product->stock + $delta),
            ]);

            return $this->transactionRepository->delete($transaction->id);
        });

        if ($result) {
            $this->activityLogService->log('Hapus Transaksi', "Transaksi \"{$product->name}\" dihapus dan stok disesuaikan kembali.");
        }

        return $result;
    }
}
