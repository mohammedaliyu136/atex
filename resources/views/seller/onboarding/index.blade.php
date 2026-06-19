@extends('layouts.atex')

@section('title', 'Become a Seller')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Become a Seller on Atex
            </h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500">
                <p>Register your business to start selling non-oil exports globally.</p>
            </div>
            <form class="mt-5 sm:flex sm:items-center" method="POST" action="{{ route('seller.onboarding.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 w-full">
                    <div class="sm:col-span-6">
                        <label for="business_name" class="block text-sm font-medium text-gray-700">Business Name</label>
                        <div class="mt-1">
                            <input type="text" name="business_name" id="business_name" value="{{ old('business_name') }}" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="business_type" class="block text-sm font-medium text-gray-700">Business Type</label>
                        <div class="mt-1">
                            <select id="business_type" name="business_type" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="SME">SME</option>
                                <option value="Cooperative">Cooperative</option>
                                <option value="Enterprise">Enterprise</option>
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="lga" class="block text-sm font-medium text-gray-700">LGA / Region</label>
                        <div class="mt-1">
                            <input type="text" name="lga" id="lga" value="{{ old('lga') }}" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="address" class="block text-sm font-medium text-gray-700">Business Address</label>
                        <div class="mt-1">
                            <textarea id="address" name="address" rows="3" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border border-gray-300 rounded-md" required>{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="registration_number" class="block text-sm font-medium text-gray-700">Registration Number (Optional)</label>
                        <div class="mt-1">
                            <input type="text" name="registration_number" id="registration_number" value="{{ old('registration_number') }}" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="tax_number" class="block text-sm font-medium text-gray-700">Tax Number (Optional)</label>
                        <div class="mt-1">
                            <input type="text" name="tax_number" id="tax_number" value="{{ old('tax_number') }}" class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                </div>
                
                <div class="mt-6 flex w-full">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto sm:text-sm">
                        Register as Seller
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
