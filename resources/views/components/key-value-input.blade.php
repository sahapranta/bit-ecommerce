@props(['name', 'data'=> '{}'])
<div class="block block-rounded shadow-sm" x-data="{name:'{{ $name }}', inputs:[], setInputs:(v, f)=>Object.entries(v).map(([key, value]) => f({ key, value })), makeInput:(value={})=>({key:'', value:'', ...value, id:(Date.now() + Math.random()).toString(36)})}" x-init="inputs=setInputs({{ $data }}, makeInput)">
    <div class="block-header block-header-default">
        <h3 class="block-title">{{ Str::replace('_', ' ', $name) }}</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option add-new-input" data-bs-toggle="tooltip" title="Add New Entry" x-on:click="inputs.push(makeInput())">
                <i class="fa fa-fw fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="block-content p-3">
        <template x-for="(input, index) in inputs" :key="`key-${input.id}`">
            <div class="row gx-2 input-box mb-3">
                <div class="col-md-4">
                    <input type="text" placeholder="key" class="form-control" x-model="input.key">
                </div>
                <div class="col-md-7">
                    <input type="text" placeholder="value" class="form-control" x-bind:name="`{{$name}}[${input.key}]`" x-model="input.value">
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <a href="#" x-on:click.prevent="inputs.splice(index, 1)">
                        <i class="fas fa-1.5x fa-times-circle text-danger"></i>
                    </a>
                </div>
            </div>
        </template>
    </div>
</div>