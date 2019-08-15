<?php


namespace App\Http\Controllers;


use App\Exports\AlunosExport;
use App\Imports\AlunoImport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function index()
    {
        $arquivos = $this->getArrayArquivos();
        $arrCollection = [];

        if ($arquivos) {
            foreach ($arquivos as $arquivo) {
                $arrCollection[] = $this->importarDados($arquivo);
                unlink($arquivo);
            }
            $arrDados = $this->mesclarDados($arrCollection);
            return $this->exportarResultado($arrDados);
        }

    }

    /**
     * @param $arrDados
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    private function exportarResultado($arrDados)
    {
        $export = new AlunosExport($arrDados);
        return Excel::download($export, date('dmyhis') . 'alunos.xls');
    }

    /**
     * @param $filepath
     * @return |null
     */
    private function importarDados($filepath)
    {
        $collection = Excel::toCollection(new AlunoImport, $filepath);
        if ($collection->get(0)) {
            return $collection->get(0)->map(function ($data) use ($filepath) {
                $fileNameArr = explode('/planilhas/', $filepath);
                $data['file'] = $fileNameArr[1];
                return $this->transformeDados($data);
            });
        }
        return null;
    }

    /**
     * @param array $data
     * @return array
     */
    private function transformeDados($data)
    {
//        $data['matricula'] = !empty(trim($data['matricula'])) ? $data['matricula'] : 'Sem Matrícula';
//        $data['nome'] = !empty(trim($data['nome'])) ? $data['nome'] : 'Sem Nome';
//        $data['escola'] = !empty(trim($data['escola'])) ? $data['escola'] : 'Sem Escola';

        return $data;
    }

    /**
     * @return array
     */
    private function getArrayArquivos()
    {
        $arrArquivos = [];
        foreach (glob(storage_path() . '/planilhas/*.csv') as $filename) {
//        foreach (glob(storage_path() . '/planilhas/*.xlsx') as $filename) {
            array_push($arrArquivos, $filename);
        }
        return $arrArquivos;
    }

    /**
     * @param $arrCollection
     * @return array
     */
    private function mesclarDados($arrCollection)
    {
        $arrDados = [];
        foreach ($arrCollection as $collection) {
            foreach ($collection->toArray() as $dados) {
                array_push($arrDados, array_values($dados));
            }
        }
        return $arrDados;
    }
}
