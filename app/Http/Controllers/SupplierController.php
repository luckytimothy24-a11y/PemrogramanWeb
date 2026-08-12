<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierService;
    protected $activityLogService;

    public function __construct(SupplierService $supplierService, ActivityLogService $activityLogService)
    {
        $this->supplierService = $supplierService;
        $this->activityLogService = $activityLogService;
    }

    public function index()
    {
        $suppliers = $this->supplierService->getAllSuppliers();

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'contact_person' => ['nullable', 'string', 'max:255'],
        ]);

        $this->supplierService->createSupplier($request->all());

        $this->activityLogService->log('Tambah Supplier', "Supplier \"{$request->input('name')}\" ditambahkan.");

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $supplier = $this->supplierService->getSupplier($id);

        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'contact_person' => ['nullable', 'string', 'max:255'],
        ]);

        $this->supplierService->updateSupplier($id, $request->all());

        $this->activityLogService->log('Ubah Supplier', "Supplier \"{$request->input('name')}\" diperbarui.");

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $supplier = $this->supplierService->getSupplier($id);
        $name = $supplier->name;

        $this->supplierService->deleteSupplier($id);

        $this->activityLogService->log('Hapus Supplier', "Supplier \"{$name}\" dihapus.");

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus!');
    }
}
