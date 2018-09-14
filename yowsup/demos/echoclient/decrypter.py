try:
    from urllib.request import urlopen
except ImportError:
    from urllib2 import urlopen
from axolotl.kdf.hkdfv3 import HKDFv3
from axolotl.util.byteutil import ByteUtil
import binascii
from Crypto.Cipher import AES
import base64
from pprint import pprint

class Decrypter():
    __arrayDeBytes = None

    def getBase64File(self):
        return base64.b64encode(bytearray(self.__arrayDeBytes))

    def getByteArray(self):
        return bytearray(self.__arrayDeBytes)

    def salvar(self, caminho):
        with open(caminho, 'wb') as f:
            f.write(bytearray(self.__arrayDeBytes))
            f.close()

    def decrypt(self, encimg, refkey, tipo = "image"):
        cryptKeys = self.getCryptKeys(tipo)
        #refkey = base64.b64decode(refkey)
        derivative = HKDFv3().deriveSecrets(refkey, binascii.unhexlify(cryptKeys), 112)
        parts = ByteUtil.split(derivative, 16, 32)
        iv = parts[0]
        cipherKey = parts[1]
        e_img = encimg[:-10]
        AES.key_size = 128
        cr_obj = AES.new(key=cipherKey, mode=AES.MODE_CBC, IV=iv)
        return cr_obj.decrypt(e_img)

    def getMediaContent(self, url, mediaKey, tipo = "image"):
        try:
            data = urlopen(url).read()
            data = self.decrypt(data, mediaKey, tipo)
            self.__arrayDeBytes = data
        except Exception as erro:
            pprint(erro)


    def getCryptKeys(self, tipo):
        if tipo == "image":
            return '576861747341707020496d616765204b657973'
        if tipo == "audio" or tipo == "ptt":
            return '576861747341707020417564696f204b657973'
        if tipo == "video":
            return '576861747341707020566964656f204b657973'
        if tipo == "document":
            return '576861747341707020446f63756d656e74204b657973'
        return "FAIL getCryptKeys"

    def getExt(self, type, filename=None):
        if type == "image":
            return ".jpeg"
        if type == "audio" or type == "ptt":
            return ".wma"
        if type == "video":
            return ".mp4"
        if type == "document":
            if filename is not None:
                return "." + filename.split(".")[-1]
        return "fail getExt"

    def getTipo(self, type):
        global tipoMensagem
        if type == "chat":
            return 1
        if type == "image":
            return 2
        if type == "audio" or type == "ptt":
            return 3
        if type == "video":
            return 4
        if type == "document":
            return 5
        return 0
