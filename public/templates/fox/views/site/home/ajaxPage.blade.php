<style>


#suggestProduct .search-ul{
    position: absolute;
    top: 70px;
    background: #ded8d8;
    padding: 21px 59px;
    right: 21px;
    border-radius: 10px;
}
#suggestProduct .design-li{
    list-style: none;
    margin: 15px 0;
}

#suggestProduct .design-li:hover{
    background: #F3F3F3;
    cursor: pointer;
}
#suggestProduct .design-li a:hover{
    color: #333;
}
</style>
<ul class="search-ul">
    @forelse ($courses as $data)
    
       @php
        $course = \App\Course::find($data);
        $image = (new \App\Helper\AppHelper)->imageExits($data->picture);
      @endphp

    <a href="{{ route('course',['course'=>$data->id,'slug'=>safeUrl($data->name)]) }}">
    <li class="design-li">
        <img src="{{$image}}" alt="" width="30">
        <strong style="padding-left: 15px">
            {{ $data->name }}
        </strong>
    </li>
</a>
@empty
<h4 style="padding-left: 15px">No Courses Available</h4>
@endforelse
</ul>