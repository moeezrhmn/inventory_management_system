<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use Str;

use function PHPUnit\Framework\fileExists;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::count();

        return view('customers.index', [
            'customers' => $customers
        ]);
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        /**
         * Handle upload an image
         */
        $existing_email = Customer::where('email', $request->email)->first();
        if($existing_email){
            return redirect()->back()->with('error', 'Email already exist!');
        }

        $image = '';
        if ($request->hasFile('photo')) {
            $image = $request->file('photo')->store('customers', 'public');
        }
        Customer::create([
            'user_id' => auth()->id(),
            'uuid' => Str::uuid(),
            'photo' => $image,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'shopname' => $request->shopname,
            'type' => $request->type,
            'account_holder' => $request->account_holder,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'address' => $request->address,
        ]);



        return redirect()
            ->route('customers.index')
            ->with('success', 'New customer has been created!');
    }

    public function show($uuid)
    {
        $customer = Customer::where('uuid', $uuid)->firstOrFail();
        $customer->loadMissing(['quotations', 'orders'])->get();

        return view('customers.show', [
            'customer' => $customer
        ]);
    }

    public function edit($uuid)
    {
        $customer = Customer::where('uuid', $uuid)->firstOrFail();
        return view('customers.edit', [
            'customer' => $customer
        ]);
    }

    public function update(UpdateCustomerRequest $request, $uuid)
    {
        $customer = Customer::where('uuid', $uuid)->firstOrFail();

        /**
         * Handle upload image with Storage.
         */
        $image = $customer->photo;
        if ($request->hasFile('photo')) {
            $filePath = public_path('storage/') . $customer->photo;
            if ($customer->photo and file_exists($filePath) ) {
                unlink($filePath);
            }
            $image = $request->file('photo')->store('customers', 'public');
        }

        $customer->update([
            'photo' => $image,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'shopname' => $request->shopname,
            'type' => $request->type,
            'account_holder' => $request->account_holder,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'address' => $request->address,
        ]);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer has been updated!');
    }

    public function destroy($uuid)
    {
        $customer = Customer::where('uuid', $uuid)->firstOrFail();
        $filePath = public_path('storage/') . $customer->photo;
        if ($customer->photo and file_exists($filePath) ) {
            unlink($filePath);
        }

        $customer->delete();

        return redirect()
            ->back()
            ->with('success', 'Customer has been deleted!');
    }
}
