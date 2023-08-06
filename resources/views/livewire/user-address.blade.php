<div>
    <!-- Shipping Address -->
    <div class="block block-rounded">
        <div class="block-header block-header-default" wire:ignore>
            <h3 class="block-title">
                {{$simple ? 'Addresses' : '2. Shipping Address' }}
            </h3>
            <div class="block-options">
                <!-- <button type="button" class="btn btn-sm btn-alt-secondary mr-3" data-bs-toggle="tooltip" data-bs-placement="left" title="Add New Address" onclick="addNewAddress">
                            <i class="fa-solid fa-plus"></i>
                        </button> -->
                <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="block-option" data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content">
            <div class="mb-4">
                <button class="btn btn-alt-warning" type="button" wire:click="openForm">
                    <i class="fa-solid fa-plus"></i>
                    Add New Address
                </button>
            </div>

            @if ($formOpen)
            <div id="new_address_form">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="checkout-fullname" name="fullname" wire:model.lazy="name" placeholder="Enter your Fullname">
                            <label class="form-label" for="checkout-fullname">Fullname <span class="text-danger">*</span></label>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="checkout-fullname" name="email" wire:model.lazy="email" placeholder="Enter your mail address">
                            <label class="form-label" for="checkout-fullname">Email <span class="text-danger">*</span></label>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('street_1') is-invalid @enderror" id="checkout-street-1" name="street_1" wire:model.lazy="street_1" placeholder="Enter your street address 1">
                        <label class="form-label" for="checkout-street-1">Street Address 1 <span class="text-danger">*</span></label>
                        @error('street_1')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row gx-3">
                    <div class="col-md-7 mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('street_2') is-invalid @enderror" id="checkout-street-2" name="street_2" wire:model.lazy="street_2" placeholder="Enter your street address 2">
                            <label class="form-label" for="checkout-street-1">Street Address 2</label>
                            @error('street_2')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-5 mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="checkout-phone" name="phone" wire:model.lazy="phone" placeholder="Enter your phone number">
                            <label class="form-label" for="checkout-phone">Phone Number <span class="text-danger">*</span></label>
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row gx-3">
                    <div class="col-md-6 mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" wire:model.lazy="country" placeholder="Enter Country Name">
                            <label class="form-label" for="country">Country</label>
                            @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('province') is-invalid @enderror" id="province" name="province" wire:model.lazy="province" placeholder="Enter State Name">
                            <label class="form-label" for="province">State</label>
                            @error('province')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row gx-3">
                    <div class="col-md-6 mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="checkout-city" name="city" wire:model.lazy="city" placeholder="Enter your city">
                            <label class="form-label" for="checkout-city">City <span class="text-danger">*</span></label>
                            @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="checkout-postal" name="postal_code" wire:model.lazy="postal_code" placeholder="Enter your postal">
                            <label class="form-label" for="checkout-postal">Postal</label>
                            @error('postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4 d-flex gap-3">
                    <button class="btn btn-alt-danger" type="button" wire:click="closeForm">
                        <i class="fa-solid fa-eye-slash"></i>
                        Hide Form
                    </button>
                    <button class="btn btn-alt-success" type="button" wire:click="saveAddress">
                        <i class="fas fa-save me-1"></i>
                        @if ($editMode)
                        Update Address
                        @else
                        Save Address
                        @endif

                        <i class="fas fa-sync fa-spin ms-2" wire:loading wire:target="saveAddress"></i>
                    </button>
                </div>
            </div>
            @else
            <div class="mb-4 pe-3" id="shipping_address_list" style="overflow-y: auto; overflow-x:hidden; scrollbar-width:thin; max-height:300px;">
                <div class="row">
                    @forelse ($addresses as $key=> $address)
                    <div class="col-md-6 mb-4" wire:key="{{ $key }}_address">
                        <div class="form-check form-block">
                            <input type="radio" value="{{ $address->id }}" class="form-check-input" id="shipping-address-{{$key}}" name="shipping_address_id" {{ $loop->first ? 'checked': ''}}>
                            <label class="form-check-label" for="shipping-address-{{$key}}">
                                <span class="d-block fw-normal p-1">
                                    <span class="d-block fw-semibold">{{ $address->name }}</span>
                                    <span class="d-block fs-sm fw-semibold text-muted">
                                        @foreach ($address->toArray() as $key=>$value)
                                        @unless (in_array($key, ['id', 'name', 'user_id', 'is_default', 'created_at', 'updated_at']) || empty($value))
                                        <span class="fw-semibold">{{ $value }}</span>
                                        @if($loop->index % 2 || $loop->last || $loop->index == 0)
                                        <br>
                                        @endif
                                        @endunless
                                        @endforeach
                                    </span>
                            </label>
                            <button wire:click="openUpdateForm('{{ $address->id }}')" type="button" class="btn btn-sm btn-outline-info" style="position:absolute;bottom:10px;right:10px;">
                                Edit
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <i class="fa-solid fa-info-circle"></i>
                            No address found, Please add new address.
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
            @endif
            @unless ($simple)
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="checkout-billing-address-same" name="billing-address-same" checked>
                    <label class="form-check-label fw-medium text-muted" for="checkout-billing-address-same">Billing address is the same</label>
                </div>
            </div>
            @endunless
        </div>
    </div>
    <!-- END Shipping Address -->

    @unless ($simple)
    <!-- Billing Address -->
    <div class="block block-rounded block-mode-hidden">
        <div class="block-header block-header-default" wire:ignore>
            <h3 class="block-title">
                3. Billing Address
            </h3>
            <div class="block-options">
                <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="block-option" data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content" id="billing-address">
            <div class="mb-4">
                <div class="row">
                    @forelse($addresses as $key=> $address)
                    <div class="col-md-6 mb-3" wire:key="{{ $key }}_address_2">
                        <div class="form-check form-block">
                            <input type="radio" value="{{ $address->id }}" class="form-check-input" id="billing-address-{{$key}}" name="billing_address_id">
                            <label class="form-check-label" for="billing-address-{{$key}}">
                                <span class="d-block fw-normal p-1">
                                    <span class="d-block fw-semibold">{{ $address->name }}</span>
                                    <span class="d-block fs-sm fw-semibold text-muted">
                                        @foreach ($address->toArray() as $key=>$value)
                                        @unless (in_array($key, ['id', 'name', 'user_id', 'is_default', 'created_at', 'updated_at']) || empty($value))
                                        <span class="fw-semibold">{{ $value }}</span>
                                        @if($loop->index % 2 || $loop->last || $loop->index == 0)
                                        <br>
                                        @endif
                                        @endunless
                                        @endforeach
                                    </span>
                            </label>
                        </div>
                    </div>
                    @empty
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <i class="fa-solid fa-info-circle"></i>
                            No address found, Please add new address.
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endunless
</div>