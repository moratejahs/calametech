import 'package:calamitech/constants/route_constants.dart';
import 'package:calamitech/core/auth/login/bloc/login_bloc.dart';
import 'package:calamitech/core/connectivity/bloc/connectivity_bloc.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocListener<LoginBloc, LoginState>(
      listener: (context, state) {
        if (state is LoginInitial) {
          context.go(RouteConstants.login);
        }
      },
      child: Scaffold(
        appBar: AppBar(
          centerTitle: true,
          title: const Text('Profile'),
        ),
        body: Padding(
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
                  child: const Text('Log Out'),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
