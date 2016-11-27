<?php

namespace App\CHD;

use Exception;

class Signatures
{
    public function get($petition, $page = 1, $init = true)
    {
        if (is_null($page)) {
            $page = 1;
        }
        $data = app('Request')->get(
            app('Path')->get(
                'PetitionSignatureList/p=petition_id='.$petition,
                '?sortDirection=ASC&pageNumber='.$page
            )
        );

        $signatures = [];

        $signaturePattern = '/<tr class="table_column_content">(?:\ )?(?P<signature>.*?)(?:\ )?<\/tr>/';
        if (preg_match_all($signaturePattern, $data, $signature)) {
            $signature = $signature['signature'];

            $signatures = $this->handlePageSignatures($signature, $page);

            if ($init) {
                $paginationPathPattern = '/for="pageNumber"[^\/]*\/\s*(?P<lastPage>\d+)/';

                if (preg_match($paginationPathPattern, $data, $lastPage)) {
                    $lastPage = $lastPage['lastPage'];

                    for ($page = $page + 1; $page <= $lastPage; ++$page) {
                        $signatures = array_merge($signatures, $this->get($petition, $page, false));
                    }
                }
            }
        }

        return $signatures;
    }

    public function handlePageSignatures($signaturesRaw, $page)
    {
        $signatures = [];

        foreach ($signaturesRaw as $index_on_page => $signature) {
            $data = [];

            $columns = explode('</td> <td', $signature);

            $signatureMetaPattern = '/(?:.*[^>])>(?P<value>.[^<]*)/';

            switch (count($columns)) {
                case 1:
                    $signatureMeta = [
                        'lastname'      => null,
                        'firstname'     => null,
                        'city'          => null,
                        'postcode'      => null,
                        'page_number'   => $page,
                        'index_on_page' => $index_on_page,
                    ];
                    break;
                case 4:
                    foreach ($columns as $key => $column) {
                        preg_match($signatureMetaPattern, $column, $signatureMeta);
                        $data[$key] = $signatureMeta['value'];
                    }

                    $signatureMeta = [
                        'lastname'      => trim($data[0]),
                        'firstname'     => trim($data[1]),
                        'city'          => trim($data[2]),
                        'postcode'      => trim(strtolower($data[3]), 'l- '),
                        'page_number'   => $page,
                        'index_on_page' => $index_on_page,
                    ];
                    break;
                default:
                    throw new Exception('unknown amount of signature details'.print_r($signatureMeta, true).print_r($signature, true));

            }

            $signatures[] = $signatureMeta;
        }

        return $signatures;
    }
}
