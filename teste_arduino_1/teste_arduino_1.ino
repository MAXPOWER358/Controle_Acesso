
#include <MFRC522.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>

/*Ligações do MFRC522 para o ESP8266 (WEMOS D1)
   RST  = D2          SDA(SS) = D4
   MOSI = D7          MISO    = D6
   SCK  = D5          GND     = GND
   3.3V = 3.3V
*/
#define LED_SAIDA D3
#define RST_PIN D2
#define SS_PIN D4
MFRC522 mfrc522(SS_PIN, RST_PIN);//cria acesso MFRC522

void setup()
{
  Serial.begin(115200);
  delay (250);
  Serial.println();
  Serial.println("Iniciando....");
  SPI.begin();//Inicia a serial SPI para o leitor
  mfrc522.PCD_Init();//inicia o leitor rfid
  pinMode(LED_SAIDA, OUTPUT); //Saída que aciona a abertura da porta
  WiFi.begin("ELETRONICA MAXPOWER", "FKE2029261992#");
  int tentativas = 0;
  while ((WiFi.status() != WL_CONNECTED) && tentativas++ < 20 ) {
    delay(500);
    Serial.print(".");
  }
  if (WiFi.status() == WL_CONNECTED)
    Serial.println("WiFi conectado ");
  delay (500);
}

char * read_RFID(char *buffer) { //função para ler as tags
  //verifica se há cartões presente e lê um cartão
  if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
    buffer = dump_byte_array(mfrc522.uid.uidByte, mfrc522.uid.size, buffer);
    //mostra o código da tag em hexadecimal
    Serial.printf("Tag UID:%s\n", buffer );
    return buffer;
  }

  else
    return NULL;
}
//Função para converter o código RFID para char  [] em hexadecimal
char * dump_byte_array(byte *buffer, byte bufferSize, char * result) {
  for (byte i = 0; i < bufferSize; i++) {
    char num[3];
    itoa(buffer[i], num, 16);
    if (buffer[i] <= 0xF)strcat(result, "0");
    strcat(result, num);
  }
  return result;
}
void loop()
{
  char code_RFID[20] = "";
  if (read_RFID(code_RFID)) { //verifica se o cartão foi lido
    if (WiFi.status() == WL_CONNECTED) { //verifica a conexão WiFi
      HTTPClient http;
      Serial.print("[HTTP]Inicio...\n");
      //configura URL, cabeçalhos e monta o metodo POST com o código da TAG

      http.begin("http://192.168.50.107/controle_acesso/consult_cliente.php");
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      int httpCode = http.POST(String("tag=") + code_RFID);
      //código será negativo em caso de erro
      if (httpCode > 0) {
        //obteve resposata do servidor
        Serial.printf("[HTTP]POST...código:%d\n", httpCode);
        //achou  o arquivo
        if (httpCode == HTTP_CODE_OK) { //conectou,atualiza o arquivo
          String payload = http.getString();
          Serial.println (payload);
          if (payload == "OK") {
            digitalWrite(LED_SAIDA, HIGH);
            delay(1000);//tempo suficiente para acionar o acesso

          }
        }
      } else
        Serial.printf("POST Erro: %s\n", http.errorToString(httpCode).c_str());
      http.end();
      delay(300);//tempo suficiente para acionar o acesso
    }
  }
}
