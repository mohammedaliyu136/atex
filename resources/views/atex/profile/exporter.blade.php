@extends('layouts.admin')

@section('title', 'My Profile | Adamawa Export Market')
@section('header_title', 'My Exporter Profile')

@section('content')
<section class="panel profile-panel" style="max-width: 700px; margin: 0 auto;">
  <div class="panel-head">
    <h2>Edit Profile Information</h2>
    <span class="status {{ $profile->verification_status }}">{{ $profile->verification_status }}</span>
  </div>
  
  <form action="{{ route('admin.profile.update') }}" method="POST" class="profile-form" style="margin-top: 25px; display: grid; gap: 15px;">
    @csrf
    <label>Contact Name
      <input name="name" required value="{{ $user->name }}">
    </label>

    <label>Email Address
      <input value="{{ $user->email }}" disabled style="background: var(--soft);">
    </label>

    <label>Phone Number
      <input name="phone" value="{{ $user->phone }}">
    </label>

    <label>Business / Cooperative Name
      <input name="business_name" required value="{{ $profile->business_name }}">
    </label>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
      <label>Registration Number
        <input name="registration_number" value="{{ $profile->registration_number }}">
      </label>
      <label>Tax Identification (TIN)
        <input name="tax_number" value="{{ $profile->tax_number }}">
      </label>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
      <label>Business Type
        <input name="business_type" required value="{{ $profile->business_type }}">
      </label>
      <label>Local Government Area (LGA)
        <input name="lga" required value="{{ $profile->lga }}">
      </label>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
      <label>Seller Brand Name
        <input name="seller_brand_name" value="{{ $profile->seller_brand_name }}">
      </label>
      <label>Fulfillment Model
        <select name="fulfillment_model" required>
          <option value="seller_direct" {{ $profile->fulfillment_model === 'seller_direct' ? 'selected' : '' }}>Seller Direct</option>
          <option value="afribidge" {{ $profile->fulfillment_model === 'afribidge' ? 'selected' : '' }}>AfriBridge Fulfillment</option>
        </select>
      </label>
    </div>

    <label class="wide">Business Address
      <textarea name="address">{{ $profile->address }}</textarea>
    </label>

    <button type="submit" class="btn primary full" style="margin-top: 10px; border: 0; cursor: pointer;">Save Exporter Profile</button>
  </form>
</section>
@endsection
