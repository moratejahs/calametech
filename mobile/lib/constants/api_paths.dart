class ApiPaths {
  static const String emulatorIP = '10.0.2.2';
  static const String wifiIP = '192.168.1.2';
  static const String port = '8000';
  static const String localApiUrl = 'http://$emulatorIP:$port/api/v1';
  static const String wifiApiUrl = 'http://$wifiIP:$port/api/v1';
  static const String productionApiUrl = 'https://domain.tld/api/v1';

  static const String getUser = '/user';
  static const String login = '/login';
  static const String signup = '/signup';
  static const String logout = '/logout';
  static const String sos = '/sos';
}
