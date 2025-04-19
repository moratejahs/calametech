class ApiPaths {
  static const String emulatorIP = '10.0.2.2';
  static const String wifiIP = '172.16.80.202';

  static const String port = '8000';
  static const String localApiUrl = 'http://$emulatorIP:$port/api/v1';
  static const String wifiApiUrl = 'http://$wifiIP:$port/api/v1';
  static const String productionApiUrl = 'https://domain.tld/api/v1';
  static const String baseUrl = wifiApiUrl;
  static const String storage = '$baseUrl/storage';

  static const String getUser = '/user';
  static const String login = '/login';
  static const String signup = '/signup';
  static const String logout = '/logout';
  static const String sos = '/sos';
  static const String report = '/report';
  static const String sosFeatured = '/sos/featured';
  static const String sosReco = '/sos/reco';
  static const String news = '$baseUrl/news';
  static const String aiApiUrl = 'https://api.openai.com/v1/chat/completions';

}
