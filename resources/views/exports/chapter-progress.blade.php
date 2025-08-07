<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            font-size: 12px;
        }

        th {
            background-color: #2f75b5;
            color: white;
            text-align: center;
        }

        td {
            text-align: center;
        }

        .chapter-title {
            font-weight: bold;
            background-color: #d9e1f2;
            font-size: 14px;
        }
    </style>
</head>

<body>
    @foreach ($chapters as $chapter)
        @if ($chapter->workProgressChapter && $chapter->workProgressChapter->details)
            <table>
                <thead>
                    <tr class="chapter-title">
                        <th colspan="20">
                            {{ $chapter->workProgressChapter->chapter_number }}.
                            {{ $chapter->workProgressChapter->chapter_name }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2"></th>
                        <th colspan="4">Condiciones contratadas</th>
                        <th colspan="3">Balance mayores y menores</th>
                        <th colspan="2">Cantidades ajustadas balance</th>
                        <th colspan="4">Avance semana</th>
                        <th colspan="4">Resumen</th>
                    </tr>
                    <tr>
                        <th>Item</th>
                        <th>Descripción</th>
                        <th>Unidad</th>
                        <th>Cantidad</th>
                        <th>Valor Unitario</th>
                        <th>Valor Total</th>
                        <th>Cant. Balance</th>
                        <th>Valor Balance</th>
                        <th>Cant. Ajustada</th>
                        <th>Valor Ajustado</th>
                        <th>Cantidad</th>
                        <th>Valor total</th>
                        <th>% Repr.</th>
                        <th>Total Cantidad</th>
                        <th>Saldo a ejecutar</th>
                        <th>Valor ejecutado</th>
                        <th>% ejecutado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chapter->workProgressChapter->details as $detail)
                        <tr>
                            <td>{{ $detail->item }}</td>
                            <td>{{ $detail->description }}</td>
                            <td>{{ $detail->unit }}</td>
                            <td>{{ number_format($detail->contracted_quantity, 0) }}</td>
                            <td>{{ number_format($detail->unit_value, 2) }}</td>
                            <td>{{ number_format($detail->partial_value, 2) }}</td>
                            <td>{{ number_format($detail->balance_quantity, 0) ?? '-' }}</td>
                            <td>{{ number_format($detail->balance_value, 2) ?? '-' }}</td>
                            <td>{{ number_format($detail->adjusted_quantity, 0) ?? '-' }}</td>
                            <td>{{ number_format($detail->adjusted_value, 2) ?? '-' }}</td>
                            <td>{{ number_format($detail->executed_quantity_sum, 0) ?? '-' }}</td>
                            <td>{{ number_format($detail->executed_total_sum, 2) ?? '-' }}</td>
                            <td>{{ $detail->execute_percentage_sum ?? '-' }}%</td>
                            <td>{{ number_format($detail->resume_quantity, 0) ?? '-' }}</td>
                            <td>
                                @if ($detail->resume_execute_value < 0)
                                    Se superó la cantidad contratada
                                @else
                                    {{ number_format($detail->resume_execute_value, 2) ?? '-' }}
                                @endif
                            </td>
                            <td>{{ number_format($detail->resume_value, 2) ?? '-' }}</td>
                            <td>{{ $detail->resume_execute_percentage ?? '-' }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
        @endif
    @endforeach
</body>

</html>
