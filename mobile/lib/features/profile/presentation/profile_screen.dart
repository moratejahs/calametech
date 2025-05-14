import 'package:calamitech/config/routing/app_routes.dart';
import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/core/shared_widgets/app_bottom_nav.dart';
import 'package:calamitech/core/utils/services/auth_user_service.dart';
import 'package:calamitech/features/auth/blocs/auth_bloc.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:pusher_channels_flutter/pusher_channels_flutter.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  late PusherChannelsFlutter pusher;
  late AuthUserService authUserService;

  @override
  void initState() {
    super.initState();
    _initPusher();
  }

  Future<void> _initPusher() async {
    authUserService = context.read<AuthUserService>();
    pusher = PusherChannelsFlutter();

    try {
      await pusher.init(
        apiKey: dotenv.env['PUSHER_APP_KEY'] ?? '',
        cluster: dotenv.env['PUSHER_APP_CLUSTER'] ?? '',
        onConnectionStateChange: (currentState, previousState) {
          debugPrint("Pusher state changed: $previousState → $currentState");
        },
        onError: (message, code, error) {
          debugPrint("Pusher error: $message (Code: $code)");
        },
        onSubscriptionSucceeded: (channelName, data) {
          debugPrint("Successfully subscribed to: $channelName");
        },
      );

      // Connect to Pusher
      await pusher.connect();
      debugPrint("Connected to Pusher: ${pusher.connectionState}");

      // Listen for events
      pusher.onEvent = _handlePusherEvent;

      final user = await authUserService.get();
      if (user != null) {
        await pusher.subscribe(channelName: 'user.${user.id}');
        debugPrint("Subscribed to channel: user.${user.id}");
      } else {
        debugPrint("No authenticated user found.");
      }
    } catch (e, stackTrace) {
      debugPrint("Exception initializing Pusher: $e");
      debugPrint("StackTrace: $stackTrace");
    }
  }

  void _handlePusherEvent(PusherEvent event) {
    debugPrint("Received Event: ${event.channelName} | ${event.eventName}");
    debugPrint("Event Data: ${event.data}");

    if (event.eventName == 'user.verified') {
      _handleUserVerifiedEvent(event);
    } else {
      debugPrint("Unhandled event: ${event.eventName}");
    }
  }

  void _handleUserVerifiedEvent(PusherEvent event) {
    context.read<AuthBloc>().add(MarkAuthUserAsVerified());
    _showUserVerifiedSnackbar();
  }

  void _showUserVerifiedSnackbar() {
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Your account has been verified.'),
        duration: Duration(seconds: 3),
        backgroundColor: Colors.green,
        behavior: SnackBarBehavior.floating,
      ),
    );
  }

  @override
  void dispose() {
    super.dispose();
    pusher.disconnect();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Profile',
          style: TextStyle(
            color: Colors.white,
            fontWeight: FontWeight.w700,
          ),
        ),
        backgroundColor: AppTheme.primaryColor,
      ),
      body: BlocConsumer<AuthBloc, AuthState>(
        listener: (context, state) {
          if (state is AuthFailure) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text(state.message),
                backgroundColor: Colors.red,
                behavior: SnackBarBehavior.floating,
                duration: const Duration(seconds: 2),
              ),
            );
          } else if (state is AuthUnAuthenticated) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(
                content: Text('Logged out.'),
                backgroundColor: Colors.green,
                behavior: SnackBarBehavior.floating,
              ),
            );
            Navigator.pushNamedAndRemoveUntil(
              context,
              AppRoutes.login,
              (route) => false,
            );
          }
        },
        buildWhen: (context, state) => state is AuthAuthenticated,
        builder: (context, state) {
          return SafeArea(
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: SingleChildScrollView(
                child: Column(
                  children: [
                    if (state is AuthAuthenticated) ...[
                      CircleAvatar(
                        radius: 50,
                        backgroundColor: AppTheme.primaryColor,
                        backgroundImage: NetworkImage("${ApiPaths.storage}${state.user.avatar}"),
                      ),
                      const SizedBox(height: 10),
                      Text(
                        state.user.name,
                        style: const TextStyle(
                          fontSize: 24,
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                        decoration: BoxDecoration(
                          color: state.user.isVerified ? Colors.green : Colors.grey,
                          borderRadius: const BorderRadius.all(
                            Radius.circular(8),
                          ),
                        ),
                        child: Text(
                          state.user.isVerified ? "Verified" : "Unverified",
                          style: const TextStyle(
                            color: Colors.white,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                      const SizedBox(height: 20),
                      SizedBox(
                        width: double.infinity,
                        child: Card(
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                          elevation: 1,
                          child: Padding(
                            padding: const EdgeInsets.symmetric(
                              vertical: 16,
                              horizontal: 16,
                            ),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                _buildProfileItem('Name', state.user.name),
                                _buildProfileItem('Email', state.user.email),
                                _buildProfileItem('Phone', state.user.phone),
                                _buildProfileItem('Address', state.user.address),
                              ],
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(height: 30),
                      ElevatedButton(
                        onPressed: () {
                          context.read<AuthBloc>().add(LogoutRequested());
                        },
                        child: BlocBuilder<AuthBloc, AuthState>(
                          builder: (context, state) {
                            return state is AuthLoading
                                ? const CircularProgressIndicator(
                                    color: Colors.white,
                                  )
                                : const Text(
                                    'Sign Out',
                                    style: TextStyle(
                                      color: Colors.white,
                                      fontSize: 18,
                                      fontWeight: FontWeight.w700,
                                    ),
                                  );
                          },
                        ),
                      ),
                    ],
                  ],
                ),
              ),
            ),
          );
        },
      ),
      bottomNavigationBar: appBottomNav(context, AppRoutes.profile),
    );
  }

  Widget _buildProfileItem(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(
              fontWeight: FontWeight.bold,
              color: Colors.grey,
              fontSize: 14,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            value,
            style: const TextStyle(
              fontSize: 16,
            ),
          ),
        ],
      ),
    );
  }
}
