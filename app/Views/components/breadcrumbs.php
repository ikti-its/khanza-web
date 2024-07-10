<?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="display: flex; flex-wrap: nowrap; list-style: none; padding: 0; margin: 0;">
            <?php foreach ($breadcrumbs as $index => $breadcrumb): ?>
                <?php $isLast = ($index === count($breadcrumbs) - 1); ?>
                <li class="breadcrumb-item" style="display: flex; align-items: center; <?= $isLast ? 'color: #6c757d; cursor: default;' : '' ?>">
                    <?php if (!empty($breadcrumb['icon'])): ?>
                        <?php if ($breadcrumb['icon'] == 'medis'): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" style="margin-right: 8px;">
                                <g clip-path="url(#clip0_1297_19722)">
                                    <path d="M10.6666 3.99967C10.6666 2.74234 10.6666 2.11434 10.2759 1.72367C9.88525 1.33301 9.25725 1.33301 7.99992 1.33301C6.74258 1.33301 6.11459 1.33301 5.72392 1.72367C5.33325 2.11434 5.33325 2.74234 5.33325 3.99967M1.33325 9.33301C1.33325 6.81901 1.33325 5.56167 2.11459 4.78101C2.89525 3.99967 4.15259 3.99967 6.66658 3.99967H9.33325C11.8473 3.99967 13.1046 3.99967 13.8853 4.78101C14.6666 5.56167 14.6666 6.81901 14.6666 9.33301C14.6666 11.847 14.6666 13.1043 13.8853 13.885C13.1046 14.6663 11.8473 14.6663 9.33325 14.6663H6.66658C4.15259 14.6663 2.89525 14.6663 2.11459 13.885C1.33325 13.1043 1.33325 11.847 1.33325 9.33301Z" stroke="#666666"/>
                                    <path d="M9 9.33301H7M8 8.33301V10.333" stroke="#666666" stroke-linecap="round"/>
                                    <path d="M7.99992 11.9993C9.47268 11.9993 10.6666 10.8054 10.6666 9.33268C10.6666 7.85992 9.47268 6.66602 7.99992 6.66602C6.52716 6.66602 5.33325 7.85992 5.33325 9.33268C5.33325 10.8054 6.52716 11.9993 7.99992 11.9993Z" stroke="#666666"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_1297_19722">
                                        <rect width="16" height="16" fill="white"/>
                                    </clipPath>
                                </defs>
                            </svg>
                            <?php elseif ($breadcrumb['icon'] == 'inventaris') : ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" style="margin-right: 8px;">
                                <path d="M13.3333 1.33301H2.66659C1.99992 1.33301 1.33325 1.93301 1.33325 2.66634V4.67301C1.33325 5.15301 1.61992 5.56634 1.99992 5.79967V13.333C1.99992 14.0663 2.73325 14.6663 3.33325 14.6663H12.6666C13.2666 14.6663 13.9999 14.0663 13.9999 13.333V5.79967C14.3799 5.56634 14.6666 5.15301 14.6666 4.67301V2.66634C14.6666 1.93301 13.9999 1.33301 13.3333 1.33301ZM12.6666 13.333H3.33325V5.99967H12.6666V13.333ZM13.3333 4.66634H2.66659V2.66634L13.3333 2.65301V4.66634Z" fill="#666666" />
                                <path d="M6 8H10V9.33333H6V8Z" fill="#666666" />
                            </svg>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (!empty($breadcrumb['url']) && !$isLast): ?>
                        <a href="<?= $breadcrumb['url'] ?>"><?= $breadcrumb['title'] ?></a>
                    <?php else: ?>
                        <?= $breadcrumb['title'] ?>
                    <?php endif; ?>
                </li>
                <?php if (!$isLast): ?>
                    <li class="breadcrumb-separator" style="display: inline; padding: 0 10px;">/</li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>
<?php endif; ?>
