<div class="col-sm-10  cv__header">
<h1 class="cv__name"> {{ $title }}</h1>
    <p class="cv__detail">{{ $user['category'][0] }}</p>
    <p class="cv__detail"> @php echo date("d M Y", strtotime(get_the_date())); @endphp - Breda, Nederland</p>
</div>