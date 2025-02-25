import 'package:calamitech/core/auth/login/bloc/login_bloc.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
        padding: const EdgeInsets.all(16.0),
        child: Center(
          child: Column(
            spacing: 20,
            children: [
              BlocBuilder<LoginBloc, LoginState>(
                builder: (context, state) {
                  if (state is LoginSuccess) {
                    return Text(state.user.email);
                  }
                  return const Text('No User');
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
      );
  }
}
