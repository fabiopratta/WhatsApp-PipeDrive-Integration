from yowsup.layers.interface                           import YowInterfaceLayer, ProtocolEntityCallback
from mysql.connector import (connection)
from yowsup.layers.auth import YowAuthenticationProtocolLayer
from decrypter import Decrypter
from PIL import Image
from yowsup.common.tools import MimeTools
from yowsup.common import YowConstants

class EchoLayer(YowInterfaceLayer):

    @ProtocolEntityCallback("message")
    def onMessage(self, messageProtocolEntity):
        print(messageProtocolEntity.getType())
        #if messageProtocolEntity.getType() == "media" and messageProtocolEntity.getMediaType() in ("image", "audio", "video"):
                       #print(messageProtocolEntity.url)
                       #print(messageProtocolEntity.fileHash)
                       #print(messageProtocolEntity.mediaKey)

                       #filename = "%s/%s%s"%("/usr/local/lib/python2.7/dist-packages/yowsup2-2.5.7-py2.7.egg/yowsup/demos/echoclient/images", messageProtocolEntity.getId(), ".jpg")
                       #filenameurl = "%s/%s.jpg"%("/usr/local/lib/python2.7/dist-packages/yowsup2-2.5.7-py2.7.egg/yowsup/demos/echoclient/images", messageProtocolEntity.getId())
                       #with open(filename, 'wb') as f:
                       #     c = Decrypter()
                       #     file = c.getMediaContent(messageProtocolEntity.url,messageProtocolEntity.fileHash)
                       #     //f.write(c.getMediaContent(messageProtocolEntity.url,messageProtocolEntity.fileHash))
                       #try:
                       #      im = Image.open(filename)
                       #      im.save(filenameurl)
                       #except Exception as e:
                       #      print("Erro ao gerar imagem %s"%e)

        if messageProtocolEntity.getType() == 'text':
            self.onTextMessage(messageProtocolEntity)
        elif messageProtocolEntity.getType() == 'media':
            self.onMediaMessage(messageProtocolEntity)

        #self.toLower(messageProtocolEntity.forward(messageProtocolEntity.getFrom()))
        self.toLower(messageProtocolEntity.ack())
        self.toLower(messageProtocolEntity.ack(True))


    @ProtocolEntityCallback("receipt")
    def onReceipt(self, entity):
        self.toLower(entity.ack())

    def onTextMessage(self,messageProtocolEntity):
        credenciais = self.getProp(YowAuthenticationProtocolLayer.PROP_CREDENTIALS)
        mensagem = messageProtocolEntity.getBody()
        #mensagem = unicode(mensagem).encode('utf-8')
        #mensagem = mensagem.encode('utf-8','ignore')
        #print(mensagem)

        #CORRETO
        mensagem = mensagem.encode('unicode_escape')

        #print(mensagem)
	con = connection.MySQLConnection(host="localhost", user="root", passwd="F25b6i87@", db="whatsapi")
	cursor = con.cursor()
	cursor.execute('INSERT INTO mensagens (data, de_phone, para_phone, message, visto) VALUES (NOW(),%s,%s,%s,%s)', (str(messageProtocolEntity.getFrom(False)),credenciais[0],mensagem,"0"))
        con.commit()
        cursor.close()
        con.close()
        arquivo = '/etc/yowsup/' + messageProtocolEntity.getFrom(False) + '.txt'
        try:
           with open(arquivo, 'r') as f:
            f.write('msg' + '\n')
            f.close()
        except IOError:
           with open(arquivo, "a") as f:
            f.write('msg' + '\n')
            f.close()
            print("Gravado"+arquivo)

    def onMediaMessage(self, messageProtocolEntity):

     #VIDEO
     if messageProtocolEntity.getMediaType() == "video":
        credenciais = self.getProp(YowAuthenticationProtocolLayer.PROP_CREDENTIALS)
        con = connection.MySQLConnection(host="localhost", user="root", passwd="F25b6i87@", db="whatsapi")
        cursor = con.cursor()
        mensagem = messageProtocolEntity.url
        m = MimeTools()
        ext = m.getExtension(messageProtocolEntity.mimeType)
        c = Decrypter()
        c.getMediaContent(messageProtocolEntity.url,messageProtocolEntity.mediaKey,"video")
        filename = "%s/%s%s"%("/var/www/html/whatsapi/Web/resources",messageProtocolEntity.getId(),ext)
        c.salvar(filename)
        serverEndereco = "%s/%s%s"%("http://187.63.83.168/whatsapi/Web/resources",messageProtocolEntity.getId(),ext)
        mensagem = "<video controls><source src='%s' type='%s'></video>"%(serverEndereco,messageProtocolEntity.mimeType)
        cursor.execute('INSERT INTO mensagens (data, de_phone, para_phone, message, visto) VALUES (NOW(),%s,%s,%s,%s)', (str(messageProtocolEntity.getFrom(False)),credenciais[0],mensagem,"0"))
        con.commit()
        cursor.close()
        con.close()

     #AUDIO
     if messageProtocolEntity.getMediaType() == "audio":
        credenciais = self.getProp(YowAuthenticationProtocolLayer.PROP_CREDENTIALS)
        con = connection.MySQLConnection(host="localhost", user="root", passwd="F25b6i87@", db="whatsapi")
        cursor = con.cursor()
        mensagem = messageProtocolEntity.url
        m = MimeTools()
        ext = m.getExtension(messageProtocolEntity.mimeType)
        c = Decrypter()
        c.getMediaContent(messageProtocolEntity.url,messageProtocolEntity.mediaKey,"audio")
        filename = "%s/%s%s"%("/var/www/html/whatsapi/Web/resources",messageProtocolEntity.getId(),ext)
        c.salvar(filename)
        serverEndereco = "%s/%s%s"%("http://187.63.83.168/whatsapi/Web/resources",messageProtocolEntity.getId(),ext)
        mensagem = "<audio src='%s' preload='auto' controls></audio>"%(serverEndereco)
        cursor.execute('INSERT INTO mensagens (data, de_phone, para_phone, message, visto) VALUES (NOW(),%s,%s,%s,%s)', (str(messageProtocolEntity.getFrom(False)),credenciais[0],mensagem,"0"))
        con.commit()
        cursor.close()
        con.close()

     #DOCUMENT
     if messageProtocolEntity.getMediaType() == "document":
        credenciais = self.getProp(YowAuthenticationProtocolLayer.PROP_CREDENTIALS)
        con = connection.MySQLConnection(host="localhost", user="root", passwd="F25b6i87@", db="whatsapi")
        cursor = con.cursor()
        mensagem = messageProtocolEntity.url
        m = MimeTools()
        ext = m.getExtension(messageProtocolEntity.mimeType)
        c = Decrypter()
        c.getMediaContent(messageProtocolEntity.url,messageProtocolEntity.mediaKey,"document")
        filename = "%s/%s%s"%("/var/www/html/whatsapi/Web/resources",messageProtocolEntity.getId(),ext)
        c.salvar(filename)
        serverEndereco = "%s/%s%s"%("http://187.63.83.168/whatsapi/Web/resources",messageProtocolEntity.getId(),ext)
        mensagem = "<a href='%s' target='_blank'>Arquivo:<br/>%s%s</a>"%(serverEndereco,messageProtocolEntity.getId(),ext)
        cursor.execute('INSERT INTO mensagens (data, de_phone, para_phone, message, visto) VALUES (NOW(),%s,%s,%s,%s)', (str(messageProtocolEntity.getFrom(False)),credenciais[0],mensagem,"0"))
        con.commit()
        cursor.close()
        con.close()

     #IMAGE
     if messageProtocolEntity.getMediaType() == "image":
         credenciais = self.getProp(YowAuthenticationProtocolLayer.PROP_CREDENTIALS)
         con = connection.MySQLConnection(host="localhost", user="root", passwd="F25b6i87@", db="whatsapi")
         cursor = con.cursor()
         mensagem = messageProtocolEntity.url
         m = MimeTools()
         ext = m.getExtension(messageProtocolEntity.mimeType)
         c = Decrypter()
         c.getMediaContent(messageProtocolEntity.url,messageProtocolEntity.mediaKey,"image")
         filename = "%s/%s%s"%("/var/www/html/whatsapi/Web/resources",messageProtocolEntity.getId(),ext)
         c.salvar(filename)
         serverEndereco = "%s/%s%s"%("http://187.63.83.168/whatsapi/Web/resources",messageProtocolEntity.getId(),ext)
         mensagem = "<img src='%s'/>"%(serverEndereco)
         cursor.execute('INSERT INTO mensagens (data, de_phone, para_phone, message, visto) VALUES (NOW(),%s,%s,%s,%s)', (str(messageProtocolEntity.getFrom(False)),credenciais[0],mensagem,"0"))
         con.commit()
         cursor.close()
         con.close()

     #LOCATION
     elif messageProtocolEntity.getMediaType() == "location":
         credenciais = self.getProp(YowAuthenticationProtocolLayer.PROP_CREDENTIALS)
         con = connection.MySQLConnection(host="localhost", user="root", passwd="F25b6i87@", db="whatsapi")
         cursor = con.cursor()
         mensagem = "LOCATION|lat:"+messageProtocolEntity.getLatitude()+"long:"+ messageProtocolEntity.getLongitude()
         cursor.execute('INSERT INTO mensagens (data, de_phone, para_phone, message, visto) VALUES (NOW(),%s,%s,%s,%s)', (str(messageProtocolEntity.getFrom(False)),credenciais[0],mensagem,"0"))
         con.commit()
         cursor.close()
         con.close()

    #VCARD
     elif messageProtocolEntity.getMediaType() == "vcard":
         credenciais = self.getProp(YowAuthenticationProtocolLayer.PROP_CREDENTIALS)
         con = connection.MySQLConnection(host="localhost", user="root", passwd="F25b6i87@", db="whatsapi")
         cursor = con.cursor()
         mensagem = "VCARD|name:"+messageProtocolEntity.getName()+"card:"+ messageProtocolEntity.getCardData()
         cursor.execute('INSERT INTO mensagens (data, de_phone, para_phone, message, visto) VALUES (NOW(),%s,%s,%s,%s)', (str(messageProtocolEntity.getFrom(False)),credenciais[0],mensagem,"0"))
         con.commit()
         cursor.close()
         con.close()
