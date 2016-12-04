<nav class="center" aria-label="Pages">
    <ul class="pagination">
        @if ($page <= 1)
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-label="Previous">
                    <span aria-hidden="true">&lt;</span>
                </a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="/page/1" tabindex="-1" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="/page/{{ ($page - 1) }}" tabindex="-1" aria-label="Previous">
                    <span aria-hidden="true">&lt;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
        @endif

        @for ($pageButton = $page - 2; $pageButton <= $page + 2; $pageButton++)
            @if ($pageButton > 0 && $pageButton <= ceil($count/env('ITEMS_PER_PAGE')))
                @if ($pageButton == $page)
                    <li class="page-item active">
                        <a class="page-link" href="/page/{{ $pageButton }}">{{ $pageButton }}<span class="sr-only">(current)</span></a>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="/page/{{ $pageButton }}">{{ $pageButton }}</a>
                    </li>
                @endif
            @endif
        @endfor

        @if ($page >= ceil($count/env('ITEMS_PER_PAGE')))
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="/page/{{ ($page + 1) }}" aria-label="Next">
                    <span aria-hidden="true">&gt;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="/page/{{ ceil($count/env('ITEMS_PER_PAGE')) }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
