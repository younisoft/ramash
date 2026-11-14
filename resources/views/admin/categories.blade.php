@extends('layouts.app')
@section('title','إدارة أقسام المتجر | RAMASH')
@section('robots','noindex,follow')
@section('content')
<section class="container section" dir="rtl">
 <a class="text-link" href="/admin">العودة إلى لوحة التحكم ←</a>
 <header class="page-heading"><h1>إدارة أقسام المتجر</h1><p>تحكّم بالأقسام التي يراها زوار المتجر.</p></header>
 <div class="notice">إخفاء القسم يخفيه من القوائم والرئيسية والبحث، ويوقف عرض منتجاته وشراءها عبر الروابط المباشرة. تبقى المنتجات والمخزون والطلبات السابقة محفوظة، ويمكنك إظهار القسم مجددًا في أي وقت.</div>
 <div class="admin-categories">
 @forelse($categories as $category)
 <article class="admin-category">
  <img src="{{ \App\Support\StoreImage::url($category->image) }}" alt="" width="72" height="88" loading="lazy">
  <div><h2>{{ $category->name }}</h2><p>{{ $category->name_en }} · {{ $category->products_count }} منتج</p><span class="category-status {{ $category->is_visible?'is-visible':'' }}">{{ $category->is_visible?'ظاهر في المتجر':'مخفي من المتجر' }}</span></div>
  <form method="post" action="/admin/categories/{{ $category->id }}/visibility">
   @csrf @method('PATCH')
   <input type="hidden" name="is_visible" value="{{ $category->is_visible?'0':'1' }}">
   <button class="button {{ $category->is_visible?'outline':'' }}" aria-label="{{ ($category->is_visible?'إخفاء قسم ':'إظهار قسم ').$category->name }}">{{ $category->is_visible?'إخفاء القسم':'إظهار القسم' }}</button>
  </form>
 </article>
 @empty<p class="empty-state">لا توجد أقسام بعد.</p>@endforelse
 </div>
</section>
@endsection
