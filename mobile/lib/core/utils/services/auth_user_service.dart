import 'dart:convert';

import 'package:calamitech/core/utils/services/secure_storage_service.dart';
import 'package:calamitech/features/auth/models/user_model.dart';

class AuthUserService {
  final SecureStorageService storage;

  AuthUserService({required this.storage});

  Future<UserModel?> get() async {
    final userString = await storage.readValue('user');

    if (userString == null) return null;

    final userMap = json.decode(userString);

    return UserModel.fromMap(userMap);
  }

  Future<bool> store(UserModel user) async {
    return await storage.writeValue('user', user.toJson());
  }

  Future<bool> delete() async {
    return await storage.deleteValue('user');
  }
}
