class ApiPaths {
  static const String emulatorIP = '10.0.2.2';
  static const String wifiIP = '192.168.1.3';
  static const String port = '8000';

  static const String localApiUrl = 'http://$emulatorIP:$port/api/v1';
  static const String wifiApiUrl = 'http://$wifiIP:$port/api/v1';

  static const String rootUrl = 'http://$wifiIP:$port';
  static const String baseUrl = wifiApiUrl;

  static const String prodRootUrl = 'https://domain.tld';
  static const String prodBaseApiUrl = 'https://domain.tld/api/v1';

  static const String login = '$baseUrl/login';
  static const String register = '$baseUrl/register';
  static const String logout = '$baseUrl/logout';
  static const String getUser = '$baseUrl/user';
  static const String news = '$baseUrl/news';
  static const String report = '$baseUrl/report';

  static const String aiApiUrl = 'https://api.openai.com/v1/chat/completions';

}
