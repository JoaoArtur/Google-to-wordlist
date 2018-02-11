<?php 
	/*
	*	GTW v0.1 - by João Artur
	*/
	class GTW {
		public $urls = [];
		public $texto = [];
		public function adicionar($obj,$texto) {
			echo "$texto: ";
			$this->$obj = str_replace(["\n","\r"],'',fgets(STDIN));
		}

		public function buscarChave() {
			for ($t=0; $t < 10; $t++) { 
				try {
					$pagina     = $t*10;
					$localidade = get_headers('https://google.com/')[1];
					$localidade = explode('?', str_replace('Location: ','',$localidade));
					$url        = $localidade[0].urlencode($this->chave).'&start='.$pagina."&".$localidade[1];
					$client     = new GuzzleHttp\Client();
					$res        = $client->request('GET', $url, [
						'headers' => [
							'User-Agent' => \Campo\UserAgent::random()
						]
					]);

					$html       = $res->getBody()->getContents();
					$doc        = new DOMDocument();
					@$doc->loadHTML($html) or die("2");

					$nodes = $doc->getElementsByTagName("a");

					for ($i = 0; $i < $nodes->length; $i++) {
					    $el = $nodes->item($i);
					    if ($el->hasAttribute("href")) {
					    	if (strstr($el->getAttribute("href"),'/url?q=') and !strstr($el->getAttribute("href"),'webcache')) {
					    		$this->urls[] = $this->filtrarUrl($el->getAttribute("href"));
					    	}
					    }
					}
					echo "\033[32m[+] ".count($this->urls)." sites encontrados\n\033[0m";
				} catch (Guzzle\Http\Exception\ClientErrorResponseException $e) {

		            $req = $e->getRequest();
		            $resp =$e->getResponse();
					echo "\033\n[31m[-] Provavelmente o Google bloqueou seu ip, tente novamente mais tarde";
		        }
		        catch (Guzzle\Http\Exception\ServerErrorResponseException $e) {

		            $req = $e->getRequest();
		            $resp =$e->getResponse();
					echo "\033\n[31m[-] Provavelmente o Google bloqueou seu ip, tente novamente mais tarde";
		        }
		        catch (Guzzle\Http\Exception\BadResponseException $e) {

		            $req = $e->getRequest();
		            $resp =$e->getResponse();
					echo "\033\n[31m[-] Provavelmente o Google bloqueou seu ip, tente novamente mais tarde";
		        }
		        catch( Exception $e){
					echo "\033\n[31m[-] Provavelmente o Google bloqueou seu ip, tente novamente mais tarde";
		        }
			}
		    echo "\n";
		}

		public function salvarLista() {
			foreach ($this->urls as $url) {


		        try {
					$client = new GuzzleHttp\Client();
					$res    = $client->request('GET', $url, [
						'headers' => [
							'User-Agent' => \Campo\UserAgent::random()
						]
					]);
					if ($res->getStatusCode() === 200) {
						$html   = $res->getBody()->getContents();
						$doc = new DOMDocument();
						@$doc->loadHTML($html) or die("2");

						$nodes = $doc->getElementsByTagName("p");
						foreach ($nodes as $el) {
					    	$this->texto[] = $el->nodeValue;
						}
						echo "\033[32m[+] $url\n";
					}
		        }
		        catch (Guzzle\Http\Exception\ClientErrorResponseException $e) {

		            $req = $e->getRequest();
		            $resp =$e->getResponse();
					echo "\033[31m[-] $url\n";
		        }
		        catch (Guzzle\Http\Exception\ServerErrorResponseException $e) {

		            $req = $e->getRequest();
		            $resp =$e->getResponse();
					echo "\033[31m[-] $url\n";
		        }
		        catch (Guzzle\Http\Exception\BadResponseException $e) {

		            $req = $e->getRequest();
		            $resp =$e->getResponse();
					echo "\033[31m[-] $url\n";
		        }
		        catch( Exception $e){
					echo "\033[31m[-] $url\n";
		        }
			}
		}

		private function filtrarUrl($url) {
			return str_replace('/url?q=', '', explode('&',$url)[0]);
		}
	}
?>