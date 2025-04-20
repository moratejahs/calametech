import 'package:calamitech/features/auth/models/user_model.dart';

abstract class IAuthRepository {
  Future<UserModel> login(String email, String password);
  Future<UserModel> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  });
  Future<void> logout();
}