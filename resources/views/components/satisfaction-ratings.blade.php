<div>
    

    @for($i = 0; $i < $rating; $i++)
        <span class="fa fa-star checked"></span>
    @endfor

    @for($x = $rating; $x < 5; $x++)
        <span class="fa fa-star "></span>
    @endfor

</div>