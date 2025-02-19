import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class SecureStorageService {
  final storage = const FlutterSecureStorage();

  Future<bool> writeValue(String key, String value) async {
    await storage.write(key: key, value: value);
    return true;
  }

  Future<dynamic> readValue(String key) async {
    return await storage.read(key: key);
  }

  Future<bool> deleteValue(String key) async {
    await storage.delete(key: key);
    return true;
  }

  Future<bool> deleteAll(String key) async {
    await storage.deleteAll();
    return true;
  }
}
