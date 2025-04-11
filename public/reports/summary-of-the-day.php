<?php

use Commons\Clock;
use Commons\Uteis;
use Dtos\IntervalDto;
use Commons\HttpRequests;
use Middleware\Authorization;
use Repositories\Users\UserRepository;
use Repositories\Car\MonthlyRepository;
use UseCases\Reports\SummaryBoxUseCase;
use UseCases\Totalizers\CloseOfDayuseCase;
use Repositories\Reports\ReportsRepository;
use Repositories\Branches\BranchesRepository;
use Repositories\Movements\MovementsRepository;
require_once __DIR__ . "./../../core/Settings.php";
try {
    Authorization::Init();
    $request =  HttpRequests::Init();
    $dataRequest = $request['date'] ?? null;
    $formart = $request['formart'] ?? null;
    if (is_null($data)) {
        $dataRequest = Clock::NowDate();
    }
    if (is_null($formart)) {
        $formart = 'Y-m-d H:i:s';
    }
    $CloseOfDayuseCase = new CloseOfDayuseCase(new MovementsRepository(), new MonthlyRepository());
    $data = $CloseOfDayuseCase->execute($dataRequest, $formart);
    // Gerar o HTML para exibir os dados
    $template = file_get_contents('template/payment_summary_day_template.html');

    // Substituir os placeholders do template com os dados dinâmicos
    $htmlContent = str_replace(
        ['{{totalRecebido}}', '{{totalDescontos}}', '{{vehicles}}', '{{separate}}', '{{accredited}}', '{{monthlypayers}}', '{{paymentMethods}}', '{{cancelations}}', '{{notExit}}'],
        [
            number_format($data['totalRecebido'], 2, ',', '.'),
            number_format($data['totalDescontos'], 2, ',', '.'),
            $data['vehicles'],
            $data['separate'],
            $data['accredited'],
            $data['monthlypayers'],
            generatePaymentMethods($data['payment_methods']),
            $data['cancelations'],
            $data['not_exit']
        ],
        $template
    );




    /*
    Authorization::Init();
    $branch = Authorization::getBranchCode();
    $inteval = new IntervalDto();
    $SummaryBoxUseCase = new SummaryBoxUseCase(new ReportsRepository());
    $userRepository =  new BranchesRepository();
    $userBranch=$userRepository->SearchBy(Authorization::getBranchCode());
    $data = $SummaryBoxUseCase->execute($inteval);
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

    */

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



// Função para gerar as linhas de pagamento
function generatePaymentMethods($paymentMethods)
{
    $rows = '';
    foreach ($paymentMethods as $payment) {
        $rows .= "<tr>
                    <td>" . htmlspecialchars($payment['name']) . "</td>
                    <td>" . $payment['quantidade'] . "</td>
                    <td>R$ " . number_format($payment['total_recebido'], 2, ',', '.') . "</td>
                </tr>";
    }
    return $rows;
}


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
                    <td>' . $formattedDate . '</td>
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
                    <td>R$ ' . Uteis::formatNumber($agreement['discounted'], 2) . '</td>
                
                </tr>';
    }
    return $rows;
}

function generatePaymentsSummary($paymentsSummary)
{
    return '<tr>
                <td>R$ ' . Uteis::formatNumber($paymentsSummary['subtotal'], 2) . '</td>
                <td>R$ ' . Uteis::formatNumber($paymentsSummary['discounted'], 2) . '</td>
                <td>R$ ' . Uteis::formatNumber($paymentsSummary['total'], 2) . '</td>
            </tr>';
}
