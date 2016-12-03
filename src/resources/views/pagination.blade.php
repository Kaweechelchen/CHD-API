<nav class="center" aria-label="Pages">
    <ul class="pagination">
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>
        @for ($pageButton = 1; $pageButton <= ($count/env('ITEMS_PER_PAGE')); $pageButton++)
            @if ($pageButton == $page)
                <li class="page-item active">
                    <a class="page-link" href="/page/{{ $pageButton }}">{{ $pageButton }}<span class="sr-only">(current)</span></a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="/page/{{ $pageButton }}">{{ $pageButton }}</a>
                </li>
            @endif
        @endfor
        <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
</nav>
