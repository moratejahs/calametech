import 'dart:io';

import 'package:calamitech/features/auth/models/user_model.dart';

abstract class IAuthRepository {
  Future<UserModel> login(String email, String password);
  Future<UserModel> register({  
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    required String phone,
    required String address,
    required File avatar,
    required File idPicture,
    required String idType,
  });
  Future<void> logout();
}
