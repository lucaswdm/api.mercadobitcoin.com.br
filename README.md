# api.mercadobitcoin.com.br

API DO MERCADOBITCOIN EM PHP

## COMO USAR
Apenas Substitua {MB_Identificador} e {MB_Segredo} pelo seu Identificador e seu Segredo, gerados em: https://www.mercadobitcoin.com.br/trade-api/configuracoes/

```
require(__DIR__ . '/class.mb.inc.php');
$MB = new MB('{MB_Identificador}', '{MB_Segredo}');
print_r($MB->get('get_account_info'));
print_r($MB->get('list_orders', array('coin_pair' => 'BRLBTC') ));
```

#BOM USO!
