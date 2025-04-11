<?php

use Commons\Uteis;
use Dtos\IntervalDto;
use Commons\HttpRequests;
use Middleware\Authorization;
use Repositories\Users\UserRepository;
use UseCases\Reports\SummaryBoxUseCase;
use Repositories\Reports\ReportsRepository;
use Repositories\Branches\BranchesRepository;
use UseCases\Reports\SummaryBoxPeriodsUseCase;

require_once __DIR__ . "./../../core/Settings.php";
try {
    Authorization::Init();
    $branch = Authorization::getBranchCode();
    $inteval = new IntervalDto(HttpRequests::Requests());
    $summaryBoxPeriodsUseCase=new SummaryBoxPeriodsUseCase(new ReportsRepository());
    $userRepository =  new BranchesRepository();
    $userBranch=$userRepository->SearchBy(Authorization::getBranchCode());
    $data = $summaryBoxPeriodsUseCase->execute($inteval);

    
    // Carregar o template HTML
    $template = file_get_contents('template/payment_summary_template.html');
    // Substituir placeholders no template
    $htmlContent = str_replace(
        ['{{title}}','{{summaryRows}}', '{{agreementsSummaryRows}}', '{{paymentsSummary}}'],
        [
            $userBranch[0]['description']." ".$userBranch[0]['cnpj'],
            generateSummaryRows($data->summary),
            generateAgreementSummaryRows($data->agreementsSummary),
            generatePaymentsSummary($data->paymentsSummary)
        ],
        $template
    );

    // Forçar o download
    $fileName = 'payment_summary_' . date('Y-m-d_H-i-s') . '.html';
    header('Content-Type: text/html');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    echo $htmlContent;
    exit;
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}

// Funções para gerar as linhas do HTML

function generateSummaryRows($summary)
{
    $rows = '';
    ///Uteis::dd($summary);
    foreach ($summary as $item) {
        $dateStr = htmlspecialchars($item['created_at']);
        $date = new DateTime($dateStr);
        $formattedDate = $date->format('d/m/Y H:i:s');
        $rows .= '<tr>
                    <td class="text-center">' . htmlspecialchars($item['park_vehicle_plate']) . '</td>
                  
                    <td>' . htmlspecialchars($item['name']) . '</td>
                    <td>' .$formattedDate . '</td>
                    <td>R$ ' . Uteis::formatNumber($item['receipt_by_box'], 2) . '</td>
                </tr>';
    }
    return $rows;
}

function generateAgreementSummaryRows($agreementsSummary)
{
    $rows = '';
    foreach ($agreementsSummary as $agreement) {
        $rows .= '<tr>
                    <td class="text-center">' . htmlspecialchars($agreement['name']) . '</td>
                    <td>R$ ' . Uteis::formatNumber($agreement['discounted']) . '</td>
                
                </tr>';
    }
    return $rows;
}

function generatePaymentsSummary($paymentsSummary)
{

    
    return '<tr>
                <td>R$ ' . Uteis::formatNumber($paymentsSummary['subtotal']) . '</td>
                <td>R$ ' . Uteis::formatNumber($paymentsSummary['discounted']) . '</td>
                <td>R$ ' . Uteis::formatNumber($paymentsSummary['total']) . '</td>
            </tr>';
}
