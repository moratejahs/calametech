import 'package:calamitech/constants/route_constants.dart';
import 'package:calamitech/core/app/cubit/navigation_cubit.dart';
import 'package:calamitech/core/auth/login/bloc/login_bloc.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';

import '../../../core/auth/login/models/user.dart';
import '../../../utils/services/secure_storage_service.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocListener<LoginBloc, LoginState>(
      listener: (context, state) {
        if (state is SignoutFailure) {
          ScaffoldMessenger.of(context)
            ..hideCurrentSnackBar()
            ..showSnackBar(
              SnackBar(
                content: Text(state.message ?? 'Failed to signout.'),
                backgroundColor: Colors.red,
                behavior: SnackBarBehavior.floating,
              ),
            );
        }

        if (state is LoginInitial) {
          context.read<NavigationCubit>().selectTab(0);
          context.go(RouteConstants.login);
        }
      },
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Center(
          child: Column(
            spacing: 20,
            children: [
              FutureBuilder<dynamic>(
                future: SecureStorageService().readValue('user'),
                builder: (context, snapshot) {
                  if (snapshot.connectionState == ConnectionState.waiting) {
                    return const CircularProgressIndicator();
                  } else if (snapshot.hasError) {
                    return const Text('Error loading user data');
                  } else if (snapshot.hasData && snapshot.data != null) {
                    final user = User.fromJson(snapshot.data!);
                    return Text(user.email);
                  } else {
                    return const Text('No User');
                  }
                },
              ),
              ElevatedButton(
                onPressed: () async {
                  if (!context.mounted) return;
                  context.read<LoginBloc>().add(const LogoutButtonPressed());
                },
                child: BlocBuilder<LoginBloc, LoginState>(
                  builder: (context, state) {
                    if (state is SignoutLoading) {
                      return const CircularProgressIndicator(
                        valueColor:
                        AlwaysStoppedAnimation<Color>(Colors.white),
                      );
                    }

                    return const Text('Log Out');
                  },
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
