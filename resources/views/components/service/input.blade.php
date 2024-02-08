@props(['service', 'checked' => false])
<div class="flex items-center gap-2">
    <?php /** @var \App\Enums\SnsServices $service */ ?>
    <input type="checkbox"
           @if($checked) checked @endif
           name="{{ $service->value }}"
           id="{{$service->value }}"
           class="form-checkbox rounded">
    <label for="{{ $service->value }}" class="ml-2">{{ $service->name }}</label>
</div>
