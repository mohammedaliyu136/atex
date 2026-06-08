@extends('layouts.admin')

@section('title', 'My Profile | Adamawa Export Market')
@section('header_title', 'My Logistics Profile')

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

    <label>Company Name
      <input name="company_name" required value="{{ $profile->company_name }}">
    </label>

    <label>Coverage Regions
      <input name="coverage_regions" value="{{ $profile->coverage_regions }}" placeholder="e.g. Nigeria, Cameroon, Niger">
    </label>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
      <label>Transport Modes
        <input name="transport_modes" value="{{ $profile->transport_modes }}" placeholder="e.g. Road Freight, Air cargo">
      </label>
      <label>Base Location (HQ)
        <input name="base_location" value="{{ $profile->base_location }}" placeholder="e.g. Yola">
      </label>
    </div>

    <label class="wide">Fleet Capacity Description
      <input name="fleet_capacity" value="{{ $profile->fleet_capacity }}" placeholder="e.g. 5 standard export cargo container vans">
    </label>

    <button type="submit" class="btn primary full" style="margin-top: 10px; border: 0; cursor: pointer;">Save Logistics Profile</button>
  </form>
</section>
@endsection
