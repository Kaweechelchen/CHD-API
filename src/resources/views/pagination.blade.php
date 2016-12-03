<nav class="center hidden-lg-down" aria-label="Pages">
    <ul class="pagination">
        @if ($page <= 1)
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="/page/{{ ($page - 1) }}" tabindex="-1" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
        @endif
        @for ($pageButton = 1; $pageButton <= ceil($count/env('ITEMS_PER_PAGE')); $pageButton++)
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

        @if ($page >= $count)
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="/page/{{ ($page + 1) }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
