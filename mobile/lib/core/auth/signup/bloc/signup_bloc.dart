import 'dart:async';
import 'package:calamitech/core/auth/signup/repositories/signup_repository.dart';
import 'package:calamitech/utils/services/secure_storage_service.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';

part 'signup_event.dart';
part 'signup_state.dart';

class SignupBloc extends Bloc<SignupEvent, SignupState> {
  final SignupRepository signupRepository;
  final SecureStorageService storage;

  SignupBloc({
    required this.signupRepository,
    required this.storage,
  }) : super(SingupInitial()) {
    on<SignupRequested>(_onSignupRequested);
  }

  Future<void> _onSignupRequested(SignupRequested event, Emitter<SignupState> emit) async {
    try {
      emit(SignupLoading());

      final response = await signupRepository.signup(event.name, event.email, event.password, event.confirmPassword);

      debugPrint('response: $response');

      if (response.containsKey('errors')) {
        emit(SignupFailure(errors: response['errors']));
        return;
      }

      emit(SignupSuccess(message: response['success']));

      debugPrint('response: $response');
    } catch (e) {
      emit(SignupFailure(message: e.toString().replaceFirst('Exception: ', '')));
    }
  }
}
