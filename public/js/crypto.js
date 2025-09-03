$(document).ready(function () {
    function fetchCryptoPrices() {
        $.ajax({
            url: '/api/crypto-prices',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                let html = '';
                $.each(data, function (symbol, info) {
                    html += `
                        <tr>
                            <th scope="row" class="text-primary">${info.baseAsset}</th>
                            <td>${Number(info.price).toLocaleString()}</td>
                            <td>${info.price_change_percent > 0 ? '+' : ''}${info.price_change_percent}%</td>
                        </tr>
                    `;
                });

                $('#crypto-prices-tbody').html(html);
            },
            error: function () {
                $('#crypto-prices-tbody').html('<tr><td colspan="3">가격 정보를 가져오지 못했습니다.</td></tr>');
            }
        });
    }


    fetchCryptoPrices();

    setInterval(fetchCryptoPrices, 10000);
});